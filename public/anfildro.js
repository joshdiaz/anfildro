var file_list = new Vue({
  el: '#file-list',

  data: {
    url: (window.location.protocol + '//' + window.location.host),
    error: '',
    files: [],
    deletion_passwords: []
  },

  created: function () {
    this.refresh();
  },

  methods: {
    refresh: function () {
      var file_list = this;
      axios
        .get(this.url + '/files')
        .then(function (response) {
          file_list.files = response.data;
        })
        .catch(function (error) {
          file_list.error = error;
        })
      ;
    },

    pretty_size: function (size) {
      var psize = size;
      var denom = 0;
      var denoms = {
        0: 'bytes',
        1: 'KB',
        2: 'MB',
        3: 'GB',
        4: 'TB'
      };

      while (psize >= 1000 && denom < 4) {
        psize = psize / 1000;
        denom++;
      }

      return (Math.round((psize * 100)) / 100) + ' ' + denoms[denom];
    },

    download: function (file) {
      window.location = this.url + '/files/' + file.uuid;
    },

    del: function (file) {
      var deletion_password = null;
      var file_list = this;

      for (candidate of this.deletion_passwords) {
        if (candidate.uuid == file.uuid) {
          deletion_password = candidate.deletion_password;
          break;
        }
      }

      if (deletion_password == null) {
        deletion_password = prompt('Enter deletion password:');
      }

      axios
        .delete(
          this.url + '/files/' + file.uuid,
          {
            params: { password: deletion_password },
            headers: { 'Content-Type': 'application/json' }
          }
        )
        .then(function (response) {
          file_list.refresh();
        })
        .catch(function (error) {
          alert('Deletion failed. Perhaps an incorrect password?');
        })
      ;
    },

    upload: function () {
      var file_list = this;
      var file_upload = document.getElementById('file-upload');
      if (file_upload.files.length == 0) {
        alert('Please select a file to upload.');
        return;
      }

      // Prepare the POST form data for Axios
      var form_upload = new FormData();
      form_upload.append('file', file_upload.files[0]);

      axios
        .post(this.url + '/files', form_upload)
        .then(function (response) {
          file_list.deletion_passwords.push(response.data);
          file_list.refresh();
          file_upload.value = null;
        })
        .catch(function (error) {
          alert('Upload failed.');
        })
      ;
    }
  }
});
