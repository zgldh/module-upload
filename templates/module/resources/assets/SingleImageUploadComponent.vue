<template>
  <el-upload class="single-image-upload-component"
             v-loading="loading"
             with-credentials
             name="file"
             accept="image/*"
             :headers="headers"
             :data="data"
             :action="action"
             :show-file-list="false"
             :on-success="handleSuccess"
             :on-error="handleError"
             :before-upload="beforeUpload">
    <img v-if="uploadObj" :src="uploadObj.url" class="preview">
    <i v-else class="el-icon-plus uploader-icon"></i>
    <span class="upload-error" v-if="error">{{error}}</span>
  </el-upload>
</template>

<script type="javascript">
  import {getXsrfToken} from 'resources/assets/js/commons/Utils.js';

  export default {
    props: {
      'value': {
        required: true
      },
      'data': {
        type: Object,
        default: {},
        required: false
      },
      'action': {
        type: String,
        default: '/upload',
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
        uploadObj: null,
        error: '',
        isUploadId: false,
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
    mounted: function () {
      if (this.value) {
        this.loadUploadObject(this.value);
      }
    },
    methods: {
      loadUploadObject(uploadId){
        this.loading = true;
        return axios.get('/upload/' + parseInt(uploadId)).then(result => {
          this.uploadObj = result.data.data;
          this.loading = false;
        }).catch(err => {
          this.error = err.data.message;
          this.loading = false;
        });
      },
      handleSuccess(res, file) {
        this.uploadObj = res.data;
        this.$emit('input', this.uploadObj.id);
        this.loading = false;
      },
      handleError(res, file) {
        let json = JSON.parse(res.message.substr(res.message.indexOf('{')));
        this.$message.error(json.message);
        this.loading = false;
      },
      beforeUpload(file) {
        const isImages = file.type.indexOf('image/') === 0;
        const isLt2M = file.size / 1024 / 1024 < this.maxSize;

        if (!isImages) {
          this.$message.error('上传图片只能是 JPG, PNG, GIF 格式!');
        }
        if (!isLt2M) {
          this.$message.error('上传图片大小不能超过 ' + this.maxSize + 'MB!');
        }
        let result = isImages && isLt2M;
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
      width: 178px;
      height: 178px;
      display: block;
    }
  }

</style>
