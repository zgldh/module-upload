<template>
  <div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>上传内容管理
        <small v-if="item.id">编辑上传内容</small>
        <small v-else>新建上传内容</small>
      </h1>
      <ol class="breadcrumb">
        <li>
          <router-link to="/"><i class="fa fa-dashboard"></i> 总览</router-link>
        </li>
        <li>
          <router-link to="/upload">上传内容管理</router-link>
        </li>
        <li class="active" v-if="item.id">编辑上传内容</li>
        <li class="active" v-else>新建上传内容</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="box box-primary">
        <!--<div class="box-header with-border">-->
        <!--</div>-->
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">

          <form class="form-horizontal" @submit="onSave">

            <div class="form-group" :class="{'has-error': item.$errors.has('name')}">
              <label for="field-name" class="col-sm-2 control-label">名字</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="field-name" v-model="item.name">
                <span class="help-block" v-if="item.$errors.has('name')">{{item.$errors.get('name')}}</span>
              </div>
            </div>
            <div class="form-group" :class="{'has-error': item.$errors.has('description')}">
              <label for="field-description" class="col-sm-2 control-label">描述</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="field-description" v-model="item.description">
                <span class="help-block"
                      v-if="item.$errors.has('description')">{{item.$errors.get('description')}}</span>
              </div>
            </div>
            <div class="form-group" :class="{'has-error': item.$errors.has('file')}">
              <label for="field-file" class="col-sm-2 control-label">文件</label>
              <div class="col-sm-10">
                <input type="file" class="form-control" id="field-file" v-on:change="onFileChange" name="file">
                <span class="help-block" v-if="item.$errors.has('file')">{{item.$errors.get('file')}}</span>
              </div>
            </div>


          </form>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <router-link-back class="btn btn-lg btn-flat btn-default pull-left">返回</router-link-back>
          <button type="submit" form="editing-form" class="btn btn-lg btn-flat btn-primary" @click="onSave"
                  :disabled="saving">
            {{saving?"保存中...":"保存"}}
          </button>
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
</template>

<script type="javascript">
  import {Vue} from 'resources/assets/js/commons/vuejs.js';
  import {alert} from 'resources/assets/js/components/SweetAlertDialogs';
  import ErrorsBuilder from 'resources/assets/js/commons/ErrorsBuilder.js';

  var resourceURL = "/upload";
  var resource = Vue.resource(resourceURL + '{/id}');
  var vueConfig = {
    data: function () {
      return {
        item: {
          "id": null,
          "name": "",
          "description": "",
          "disk": "upload",
          "path": "",
          "size": null,
          "user_id": null,
          "uploadable_id": null,
          "uploadable_type": "",
          "file": null,
          $errors: ErrorsBuilder()
        },
        saving: false,
      };
    },
    beforeRouteEnter (to, from, next) {
      if (to.params.id) {
        resource.get({id: to.params.id}).then(function (result) {
          next(function (vm) {
            vm.item = result.data.data;
            vm.item.$errors = ErrorsBuilder();
          })
        }).catch(function (err) {
          next(false);
        });
      }
      else {
        next();
      }
    },
    methods: {
      onFileChange: function (val) {
        this.item[val.target.attributes.item('name').value] = val.target.files[0];
      },
      onSave: function (event) {
        this.saving = true;
        this.item.$errors.removeAll();

        var promise = null;
        let payload = $.extend(true, {}, this.item);
        if (payload.id) {
          promise = resource.update({id: payload.id}, payload).then(function (result) {
            window.toastr["success"]("编辑已保存");
            return result.data.data;
          });
        }
        else {
          promise = resource.save(payload).then(function (result) {
            window.toastr["success"]("新增成功");
            return result.data.data;
          });
        }

        promise.then(function (data) {
          return resource.get({id: data.id});
        }).then(function (result) {
          this.saving = false;
          this.item = result.data.data;
          this.item.$errors = ErrorsBuilder();
        }.bind(this)).catch(function (err) {
          this.saving = false;
          if (err.status == 422) {
            this.item.$errors.setAll(err.body);
            this.item.$errors.focusFirstErrorField();
          }
          else {
            alert(err.data.message);
          }
        }.bind(this));

        return false;
      },
    }
  };
  export default vueConfig;

</script>

<style lang="scss">

</style>
