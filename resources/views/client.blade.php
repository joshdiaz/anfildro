@verbatim
<!DOCTYPE html>
<html>
<head>
  <title>DEAD DROP</title>
  <link href="bootstrap.min.css" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    .uuid { width: 22em; }
    .file-size { width: 6em; text-align: right; }
    .upload-btn {
      width: 6em;
      text-align: right;
      vertical-align: middle !important;
    }
    .file-actions {
      width: 12em;
      text-align: right;
      vertical-align: middle !important;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>DEAD DROP</h1>
    <table id="file-list" class="table table-striped">
      <thead>
        <tr>
          <th>UUID</th>
          <th>Filename</th>
          <th class="text-right">Size</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="error" class="danger">
          <td colspan="4">
            Error retrieving file list: {{ error.response.status }} {{ error.response.statusText }}
          </td>
        </tr>
        <tr v-if="files.length == 0" class="warning">
          <td colspan="4">
            There are no files yet.
          </td>
        </tr>
        <tr v-for="file in files">
          <td class="uuid">{{ file.uuid }}</td>
          <td class="file-name">{{ file.original_filename }}</td>
          <td class="file-size">{{ pretty_size(file.size) }}</td>
          <td class="file-actions">
            <button class="btn btn-sm btn-default" v-on:click="download(file)">DOWNLOAD</button>
            <button class="btn btn-sm btn-danger" v-on:click="del(file)">x</button>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr v-if="!error" class="info">
          <td colspan="3">
            <div class="component form-inline" v-if="!upload_in_progress">
              <div class="form-group">
                <label for="file-upload" class="control-label">Select file to upload:</label>
                <input type="file" id="file-upload" class="input-file" v-bind:disabled="upload_in_progress" />
              </div>
            </div>
            <div v-if="upload_in_progress" class="text-center">
              <span>{{ upload_progress }}% Complete</span>
            </div>
          </td>
          <td class="upload-btn"><button class="btn btn-sm btn-primary" v-on:click="upload()" v-bind:disabled="upload_in_progress">UPLOAD</button></td>
        </tr>
      </tfoot>
    </table>
  </div>
  <script src="bundle.js"></script>
</body>
</html>
@endverbatim
