<template>
  <div class="admin-list-page">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>上传内容
        <small>列表</small>
      </h1>
      <ol class="breadcrumb">
        <li>
          <router-link to="/"><i class="fa fa-dashboard"></i> 总览</router-link>
        </li>
        <li>上传内容</li>
        <li class="active">列表</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="box">
        <div class="box-header with-border">
          <div class="buttons">
            <el-button type="danger" @click="onBundleDelete" icon="delete" :disabled="selectedItems.length==0">
              批量删除
            </el-button>
          </div>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body datatable-loading-section">
          <div class="search">
            <el-form :inline="true" :model="searchForm" ref="searchForm">
              <el-form-item label="Name">
                <el-input v-model="searchForm.name" placeholder="Name" column="name" operator="like"></el-input>
              </el-form-item>
              <el-form-item label="Email">
                <el-input v-model="searchForm.email" placeholder="Email" column="email" operator="like"></el-input>
              </el-form-item>
              <el-form-item label="Is Active">
                <el-select v-model="searchForm.is_active" placeholder="Select..." clearable
                           column="is_active" operator="=">
                  <el-option label="Active" value="1"></el-option>
                  <el-option label="Inactive" value="0"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="Created At">
                <el-date-picker
                        v-model="searchForm.created_at"
                        type="daterange"
                        placeholder="选择日期范围"
                        clearable
                        column="created_at"
                        operator="range">
                </el-date-picker>
              </el-form-item>

              <el-form-item>
                <el-button-group>
                  <el-button type="primary" @click="onSubmitSearch">查询</el-button>
                  <el-button type="button" @click="onResetSearch">清空</el-button>
                </el-button-group>
              </el-form-item>
            </el-form>
          </div>

          <div class="datatable-container">
            <!-- 采用 datatables 标准-->
            <el-row class="tools">
              <el-col :span="4">
                <span class="page-size">显示
                <el-select v-model="pagination.pageSize" style="width: 80px"
                           @change="onPageSizeChange">
                  <el-option
                          v-for="item in pagination.pageSizeList"
                          :key="item.value"
                          :label="item.label"
                          :value="item.value">
                  </el-option>
                </el-select>
                  项结果</span>
              </el-col>
              <el-col :span="12">
                <el-pagination
                        @current-change="onPageChange"
                        :current-page="pagination.currentPage"
                        :page-size="pagination.pageSize==-1?1:pagination.pageSize"
                        :layout="pagination.pageSize==-1?'total':'total, prev, pager, next, jumper'"
                        :total="pagination.totalCount">
                </el-pagination>
              </el-col>
              <el-col :span="8">
                <el-input class="auto-search" style="width: 200px;float: right;"
                          placeholder="模糊搜索"
                          v-model="datatablesParameters.search.value"
                          :icon="datatablesParameters.search.value?'close':'search'"
                          :on-icon-click="onAutoSearchIconClick"
                          @change="onAutoSearchChanged">
                </el-input>
              </el-col>
            </el-row>

          </div>
          <div class="datatable" style="margin-top:1em;">
            <el-table
                    :data="tableData"
                    border
                    style="width: 100%"
                    max-height="500"
                    :default-sort="defaultSort"
                    @sort-change="onSortChange"
                    @selection-change="onSelectionChange"
                    ref="table"
            >
              <el-table-column
                      fixed
                      type="selection"
                      width="55">
              </el-table-column>
              <el-table-column
                      prop="name"
                      label="Name"
                      sortable="custom"
                      show-overflow-tooltip
                      width="180">
              </el-table-column>
              <el-table-column
                      prop="description"
                      label="Description"
                      sortable="custom"
                      show-overflow-tooltip>
              </el-table-column>
              <el-table-column
                      prop="disk"
                      label="Disk"
                      sortable="custom"
                      show-overflow-tooltip>
              </el-table-column>
              <el-table-column
                      prop="size"
                      label="Size"
                      sortable="custom"
                      show-overflow-tooltip>
              </el-table-column>
              <el-table-column
                      prop="created_at"
                      label="Created At"
                      sortable="custom"
                      searchable="false"
                      show-overflow-tooltip>
              </el-table-column>
              <el-table-column
                      fixed="right"
                      label="操作"
                      width="120">
                <template scope="scope">
                  <el-button-group>
                    <el-button @click="onEditClick(scope.row,scope.column,scope.$index,scope.store)" type="default"
                               size="small" icon="edit" title="编辑"></el-button>
                    <el-button @click="onDeleteClick(scope.row,scope.column,scope.$index,scope.store)" type="danger"
                               size="small" icon="delete" title="删除"></el-button>
                  </el-button-group>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <el-button type="primary" @click="onCreate" icon="plus">添加用户</el-button>
          <el-button type="danger" @click="onBundleDelete" icon="delete" :disabled="selectedItems.length==0">
            批量删除
          </el-button>
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
</template>

<script type="javascript">
  import {mixin} from "resources/assets/js/commons/ListHelpers.js";

  export default {
    mixins: [mixin],
    data: function () {
      let data = {
        resource: '/upload',
        datatablesParameters: {
          order: [{column: 'created_at', dir: 'desc'}],
        },
        searchForm: {
          name: null,
          email: null,
          is_active: null,
          created_at: null
        }
      };
      return data;
    },
    methods: {
      onEditClick: function (row, column, $index, store) {
        return this.$router.push('/upload/' + row.id + '/edit');
      },
      onDeleteClick: function (row, column, $index, store) {
        return this._onDeleteClick({
          url: '/upload/' + row.id,
          params: {},
          confirmText: '确认要删除吗？',
          messageText: '删除完毕'
        }).then(result => {
          this.tableData.splice($index, 1);
          this.pagination.totalCount--;
        });
      },
      onBundleDelete: function () {
        return this.$confirm("确认要删除 " + this.selectedItems.length + " 项么？", '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          return this._onBundle('delete');
        }).then(result => {
          this.$message({
            type: 'success',
            message: "删除完毕"
          });
          return this.queryTableData();
        });
      },
    }
  };

</script>

<style lang="scss">

</style>
