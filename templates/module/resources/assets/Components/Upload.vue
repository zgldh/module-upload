<template>
  <el-upload class="upload-component"
             v-loading="loading"
             with-credentials
             name="file"
             :accept="accept"
             :multiple="false"
             :headers="headers"
             :data="data"
             :action="action"
             :show-file-list="true"
             :file-list="fileList"
             :on-preview="handlePreview"
             :on-success="handleSuccess"
             :on-error="handleError"
             :on-remove="handleRemove"
             :before-upload="beforeUpload">
    <el-button size="small" type="primary">点击上传</el-button>
    <span v-if="maxSize" slot="tip" class="el-upload__tip">上传文件不能超过500kb</span>
    <span class="upload-component--error" v-if="error">{{error}}</span>
  </el-upload>
</template>

<script type="javascript">
  import { getXsrfToken } from 'resources/assets/js/commons/Utils.js';

  export default {
    props: {
      'value': {
        required: true
      },
      'data': {
        type: Object,
        default: function () {
          return {}
        },
        required: false
      },
      'action': {
        type: String,
        default: '/upload',
        required: false
      },
      'accept': {
        type: String,
        default: '*/*',
        required: false
      },
      // MB
      'maxSize': {
        type: Number,
        default: 2,
        required: false
      }
    },
    data() {
      return {
        fileList: [],
        error: '',
        loading: false
      };
    },
    computed: {
      headers: function () {
        let headers = {
          'Accept': 'application/json, text/plain, */*',
          'X-XSRF-TOKEN': getXsrfToken()
        };
        return headers;
      }
    },
    watch: {
      value: function (newValue, oldValue) {
        if (newValue) {
          this.fileList = [this.value];
        }
        else {
          this.fileList = [];
        }
      }
    },
    mounted: function () {
      if (this.value) {
        this.fileList = [this.value];
      }
    },
    methods: {
      handlePreview(file) {
        console.log('handlePreview', file);
        var url = file.url;
        window.open(url, '_blank');
      },
      handleSuccess(res, file, fileList) {
        this.fileList = [res.data];
        this.$emit('input', res.data);
        this.loading = false;
      },
      handleError(res, file, fileList) {
        let json = JSON.parse(res.message.substr(res.message.indexOf('{')));
        this.$message.error(json.message);
        this.loading = false;
      },
      handleRemove(file, fileList) {
        return axios.post('/upload/' + file.id, {_method: 'delete'}).then(result => {
          if (result.status == 200) {
            return true;
          }
          else {
            throw new Error('delete failed');
          }
        });
      },
      beforeUpload(file) {
        const isLt2M = file.size / 1024 / 1024 < this.maxSize;
        if (!isLt2M) {
          this.$message.error('上传图片大小不能超过 ' + this.maxSize + 'MB!');
        }
        let result = isLt2M;
        if (result) {
          this.loading = true;
        }
        return result;
      }
    }
  }
</script>

<style lang="scss">
  .single-image-upload-component {
    input[type="file"] {
      display: none;
    }
    .upload-error {
      color: #FF4949;
      position: absolute;
      left: 10px;
      top: 0;
    }
    .el-upload {
      border: 1px dashed #d9d9d9;
      border-radius: 6px;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      &:hover {
        border-color: #20a0ff;
      }
    }

    .uploader-icon {
      font-size: 28px;
      color: #8c939d;
      width: 178px;
      height: 178px;
      line-height: 178px;
      text-align: center;
    }

    .preview {
      /*width: 178px;*/
      height: 178px;
      display: block;
    }
  }

</style>
