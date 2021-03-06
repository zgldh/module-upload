<template>
  <el-upload class="upload-component"
             v-loading="loading"
             with-credentials
             name="file"
             :accept="accept"
             :multiple="multiple"
             :headers="headers"
             :data="data"
             :action="action"
             :show-file-list="true"
             :file-list="fileList"
             :list-type="listType"
             :on-preview="handlePreview"
             :on-success="handleSuccess"
             :on-error="handleError"
             :on-remove="handleRemove"
             :before-upload="beforeUpload">
    <el-button size="small" type="primary" :disabled="buttonDisabled">
      <i :class="icon"></i>
      {{$t('module_upload.terms.upload_button')}}
    </el-button>
    <span v-if="maxSize && listType!='picture-card'" slot="tip" class="el-upload__tip">
      {{$t('module_upload.terms.max_size',{count:maxSize})}}
    </span>
    <div v-else-if="maxSize" slot="tip" class="el-upload__tip">
      {{$t('module_upload.terms.max_size',{count:maxSize})}}
    </div>
    <span class="upload-component--error" v-if="error">{{error}}</span>
  </el-upload>
</template>

<script type="javascript">
  import { getXsrfToken } from 'resources/assets/js/commons/Utils.js';
  import { loadLanguages } from 'resources/assets/js/commons/LanguageHelper';

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
      'icon': {
        type: String,
        default: 'icon-cloud-upload',
        required: false
      },
      'action': {
        type: String,
        default: '/upload',
        required: false
      },
      'listType': {
        type: String,
        default: 'text', //text | picture | picture-card
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
      },
      'maxLength': {
        type: Number,
        default: 0,
        required: false
      },
      'multiple': {
        type: Boolean,
        default: false,
        required: false
      },
    },
    data() {
      return {
        fileList: [],
        error: '',
        loading: false,
        uploadingCount: 0
      };
    },
    computed: {
      headers: function () {
        let headers = {
          'Accept': 'application/json, text/plain, */*',
          'X-XSRF-TOKEN': getXsrfToken()
        };
        return headers;
      },
      buttonDisabled: function () {
        if (this.fileList.length >= this.maxLength && this.maxLength > 0) {
          return true;
        } else {
          return false;
        }
      }
    },
    watch: {
      value: function (newValue, oldValue) {
        if (newValue) {
          if (this.multiple) {
            if (this.uploadingCount === 0) {
              this.fileList = JSON.parse(JSON.stringify(this.value));
            }
          }
          else {
            this.fileList = [this.value];
          }
        }
        else {
          this.fileList = [];
        }
      },
    },
    created: function () {
      loadLanguages('module_upload').then(() => {
        this.$forceUpdate();
      })
    },
    mounted: function () {
      if (this.value) {
        if (this.multiple) {
          this.fileList = JSON.parse(JSON.stringify(this.value));
        }
        else {
          this.fileList = [this.value];
        }
      }

    },
    methods: {
      handlePreview(file) {
        var url = file.url;
        window.open(url, '_blank');
      },
      handleSuccess(res, file, fileList) {
        this.uploadingCount--;
        if (this.multiple) {
          this.fileList = fileList;
          if (this.uploadingCount === 0) {
            this.$emit('input', this.fileList.map(item => item.hasOwnProperty('response') ? item.response.data : item));
          }
        }
        else {
          this.fileList = [res.data];
          this.$emit('input', res.data);
        }
        this.loading = false;
      },
      handleError(res, file, fileList) {
        let json = JSON.parse(res.message.substr(res.message.indexOf('{')));
        this.$message.error(json.message);
        this.loading = false;
      },
      handleRemove(file, fileList) {
        var vm = this;
        return axios.post('/upload/' + file.id, {_method: 'delete'}).then(result => {
          if (result.status == 200) {
            if (vm.multiple) {
              var fileIndex = vm.fileList.findIndex(item => {
                return item.id == result.data.data.id;
              });
              vm.fileList.splice(fileIndex, 1);
              this.$emit('input', vm.fileList);
            }
            else {
              vm.fileList = [];
              this.$emit('input', null);
            }
            return true;
          }
          else {
            throw new Error('delete failed');
          }
        });
      },
      beforeUpload(file) {
        if (this.fileList.length + this.uploadingCount >= this.maxLength && this.maxLength > 0) {
          return false;
        }
        const isLt2M = file.size / 1024 / 1024 < this.maxSize;
        if (!isLt2M) {
          this.$message.error($t('module_upload.terms.max_size', {count: this.maxSize}));
        }
        let result = isLt2M;
        if (result) {
          this.loading = true;
          this.uploadingCount++;
        }
        return result;
      }
    }
  };
</script>

<style lang="scss">
  .upload-component {
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

    img.el-upload-list__item-thumbnail {
      width: auto;
    }
  }

</style>
