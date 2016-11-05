<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Represents a File on our dead drop
 *
 * @author jdiaz
 * @property int $id
 * @property string $uuid
 * @property string $original_filename Original filename as sent by uploader
 * @property string $deletion_password Hashed deletion password
 * @property int $size File size
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class File extends Model
{
    /**
     * @inheritDoc
     */
    protected $hidden = ['id', 'deletion_password'];

    /**
     * Find a file by UUID or fail
     *
     * @var File
     */
    public static function findByUuidOrFail ($uuid)
    {
        return static::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Turn this file into a BinaryFileResponse suitable for returning from
     * a route closure.
     *
     * @return BinaryFileResponse
     */
    public function asBinaryFileResponse ()
    {
        return
            (new BinaryFileResponse($this->getLocalPath()))
                ->setContentDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    $this->original_filename
                )
        ;
    }

    /**
     * Determine the local path to this file.
     *
     * @return string
     */
    public function getLocalPath ()
    {
        return env('ANFILDRO_FILES', '../storage/files') . DIRECTORY_SEPARATOR . $this->uuid;
    }

    /**
     * Create a new File.
     *
     * @param  Request $request
     * @return array
     */
    public static function createFromRequest (Request $request)
    {
        $uploaded_file = $request->file('file');
        if (is_null($uploaded_file)) {
            throw new Exception('Invalid request: no file provided.', 409);
        }

        $password = str_random(10);
        $file = new static();
        $file->uuid = Uuid::uuid4()->toString();
        $file->original_filename = $uploaded_file->getClientOriginalName();
        $file->deletion_password = (new BcryptHasher())->make($password);
        $file->size = $uploaded_file->getSize();
        $file->save();

        move_uploaded_file($uploaded_file->getPathname(), $file->getLocalPath());

        return [
            'uuid' => $file->uuid,
            'deletion_password' => $password
        ];
    }

    /**
     * Check the provided cleartext password against this file's deletion pw
     * and the admin deletion pw.
     *
     * @param  string $password
     * @return bool
     */
    public function deletionPermitted ($password)
    {
        return (
            (new BcryptHasher())->check($password, $this->deletion_password)
            || $password === env('ANFILDRO_PASSWORD', false)
        );
    }

    /**
     * Delete this record and the local file it represents.
     *
     * @return bool
     */
    public function delete ()
    {
        return (
            parent::delete()
                ? $this->deleteLocal()
                : false
        );
    }

    /**
     * Deletes the local file this record represents. Wraps the unlink() call
     * in a try/catch so that we can delete a file record that has lost its
     * local file.
     *
     * @return bool
     */
    protected function deleteLocal ()
    {
        try {
            return unlink($this->getLocalPath());
        } catch (Exception $e) {
            // Ignore - allow record to be deleted
        }

        return false;
    }
}
