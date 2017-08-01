<template>
  <div class="admin-editor-page">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>上传内容管理
        <small v-if="form.id">编辑上传内容</small>
        <small v-else>新建上传内容</small>
      </h1>
      <ol class="breadcrumb">
        <li>
          <router-link to="/"><i class="fa fa-dashboard"></i> 总览</router-link>
        </li>
        <li>
          <router-link to="/upload">上传内容管理</router-link>
        </li>
        <li class="active" v-if="form.id">编辑上传内容</li>
        <li class="active" v-else>新建上传内容</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="box box-default">

        <div class="box-header with-border">
          <el-button type="default" @click="onCancel" icon="close">返回</el-button>
          <el-button type="primary" @click="onSave" icon="check" :loading="saving||loading">
            保存
          </el-button>
        </div>
        <!-- /.box-header -->

        <!-- form start -->
        <div class="box-body">
          <el-alert class="missing-errors" v-if="missingErrors.length" v-for="errorMessage in missingErrors"
                    :key="errorMessage"
                    :title="errorMessage" type="error" show-icon></el-alert>

          <!-- form start -->
          <el-form ref="form" :model="form" label-width="200px" v-loading="loading">
            <el-form-item label="ID" v-if="form.id">
              <el-input v-model="form.id" disabled></el-input>
            </el-form-item>
            <el-form-item label="Name" prop="name" :error="errors.name">
              <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="Description" prop="description" :error="errors.description">
              <el-input v-model="form.description"></el-input>
            </el-form-item>
            //TODO file upload
            <el-form-item label="Created At" v-if="form.id">
              <el-input v-model="form.created_at" disabled></el-input>
            </el-form-item>
          </el-form>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <el-button type="default" @click="onCancel" icon="close">返回</el-button>
          <el-button type="primary" @click="onSave" icon="check" :loading="saving||loading">
            保存
          </el-button>
        </div>

      </div>

    </section>
    <!-- /.content -->


    <!-- Main content -->
    <section class="content">

      <div class="box box-primary">
        <!--<div class="box-header with-border">-->
        <!--</div>-->
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">

          <form class="form-horizontal" @submit="onSave">

            <div class="form-group" :class="{'has-error': form.$errors.has('name')}">
              <label for="field-name" class="col-sm-2 control-label">名字</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="field-name" v-model="form.name">
                <span class="help-block" v-if="form.$errors.has('name')">{{form.$errors.get('name')}}</span>
              </div>
            </div>
            <div class="form-group" :class="{'has-error': form.$errors.has('description')}">
              <label for="field-description" class="col-sm-2 control-label">描述</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="field-description" v-model="form.description">
                <span class="help-block"
                      v-if="form.$errors.has('description')">{{form.$errors.get('description')}}</span>
              </div>
            </div>
            <div class="form-group" :class="{'has-error': form.$errors.has('file')}">
              <label for="field-file" class="col-sm-2 control-label">文件</label>
              <div class="col-sm-10">
                <input type="file" class="form-control" id="field-file" v-on:change="onFileChange" name="file">
                <span class="help-block" v-if="form.$errors.has('file')">{{form.$errors.get('file')}}</span>
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
  import {mixin} from "resources/assets/js/commons/EditorHelper.js";

  export default  {
    mixins: [mixin],
    data: function () {
      return {
        form: {
          id: null,
          name: '',
          description: '',
        }
      };
    },
    components: {},
    computed: {
      resource: function () {
        var resourceURL = '/upload';
        return (this.form.id ? resourceURL + '/' + this.form.id : resourceURL);// + '?_with=roles,permissions';
      }
    },
    created: function () {
      this.loading = true;
      let loads = [];
      if (this.$route.params.id) {
        this.form.id = this.$route.params.id;
        loads.push(axios.get(this.resource));
      }

      Promise.all(loads).then(results => {
        this.form = results[0].data.data;
        this.loading = false;
      }).catch(err => {
        this.loading = false;
      });
    },
    methods: {
      onSave: function (event) {
        this._onSave(event).then(result => {
          this.$router.replace('/upload/' + result.data.data.id + '/edit');
          this.form = result.data.data;
          this.form.permissions = this.form.permissions.map(permission => permission.id);
        });
      },
      onCancel: function (event) {
        this.$router.back();
      },
    }
  };
</script>

<style lang="scss" scoped>

</style>
