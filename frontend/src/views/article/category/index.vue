<template>
  <div class="app-container">
    <category-filter :list-query="listQuery" />
    <br>
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      border
      fit
      highlight-current-row
      @selection-change="handleSelectionChange"
      @sort-change="handleSort"
    >
      <el-table-column
        align="center"
        type="selection"
        width="55"
      />
      <el-table-column
        align="center"
        prop="id"
        label="No."
        sortable="custom"
        width="95"
      >
        <template slot-scope="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column
        label="Name"
        prop="name"
        sortable="custom"
      >
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        label="Order"
        prop="sort"
        width="95"
        sortable="custom"
      >
        <template slot-scope="scope">
          {{ scope.row.sort }}
        </template>
      </el-table-column>
      <el-table-column
        label="Created at"
        prop="created_at"
        width="210"
        align="center"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column
        v-if="editCategoryRole || deleteCategoryRole"
        label="Operations"
        align="center"
        width="150"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="scope">
          <el-button
            v-if="editCategoryRole"
            type="primary"
            @click="handleUpdate(scope.row)"
          >
            Edit
          </el-button>

          <el-popover
            v-if="deleteCategoryRole"
            :ref="`popover-${scope.$index}`"
            trigger="click"
            placement="top"
            width="100"
          >
            <p class="el-icon-warning">Confrim Deletion</p>
            <div style="text-align: right; margin: 0">
              <el-button
                type="text"
                @click="scope._self.$refs[`popover-${scope.$index}`].doClose()"
              >
                Cancel
              </el-button>
              <el-button
                type="primary"
                @click="doConfirm(scope.row.id,scope.$index)"
              >
                OK
              </el-button>
            </div>
            <el-button
              slot="reference"
              type="danger"
            >
              Delete
            </el-button>
          </el-popover>
        </template>
      </el-table-column>
    </el-table>
    <template>
      <pagination
        :total="total"
        :page.sync="listQuery.page"
        :limit.sync="listQuery.limit"
        @pagination="fetchData()"
      />
    </template>

    <el-dialog
      :title="textMap[dialogStatus]"
      :visible.sync="dialogFormVisible"
    >
      <el-form
        ref="dataForm"
        v-loading="dialogLoading"
        :model="temp"
        :rules="rules"
      >
        <el-form-item
          label="Name"
          prop="name"
          :label-width="formLabelWidth"
        >
          <el-input
            v-model="temp.name"
            autocomplete="off"
          />
        </el-form-item>
        <el-form-item
          label="Order"
          prop="sort"
          :label-width="formLabelWidth"
        >
          <el-input
            v-model="temp.sort"
            autocomplete="off"
          />
        </el-form-item>
      </el-form>
      <div
        slot="footer"
        class="dialog-footer"
      >
        <el-button @click="dialogFormVisible = false">Cancel</el-button>
        <el-button
          type="primary"
          @click="dialogStatus==='create'?createData():updateData()"
        >OK</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getList, deleteCategory, deleteMutiCategory, createCategory, updateCategory } from '@/api/article/category'
import Pagination from '@/components/Pagination'
import CategoryFilter from './filter'
import { checkPermission } from '@/utils/permission'

export default {
  components: {
    Pagination,
    CategoryFilter
  },
  data () {
    return {
      // 按钮权限
      editCategoryRole: checkPermission(['admin-create-article-category']),
      deleteCategoryRole: checkPermission(['admin-delete-article-category']),
      list: null,
      multipleSelection: [],
      listLoading: true,
      dialogLoading: false,
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
        name: undefined,
        order: '',
        sorted: ''
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: 'Edit Category',
        create: 'Add Category'
      },
      temp: {
        id: undefined,
        name: '',
        sort: 1
      },
      formLabelWidth: '100px',
      rules: {
        name: [{ required: true, message: 'Name is required.', trigger: 'blur' }],
        sort: [
          { required: true, message: 'Order is required.', trigger: 'blur' },
          { pattern: /^[0-9]+$/, message: 'a number is needed.', trigger: 'blur' }
        ]
      }
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      this.listLoading = true
      getList(this.listQuery).then(response => {
        const pagination = response.result.meta.pagination
        this.list = response.result.data
        this.total = pagination.total
        this.listQuery.page = pagination.current_page
        this.listQuery.limit = pagination.per_page
        this.listLoading = false
      })
    },
    handleFilter () {
      if (!this.listQuery.name) {
        return false
      }
      this.listQuery.page = 1
      this.fetchData()
    },
    deleteData (id) {
      this.listLoading = true
      deleteCategory(id).then(response => {
        this.listLoading = false
        this.$message({
          message: 'successfully deleted.',
          type: 'success'
        })
        this.fetchData()
      })
    },
    doConfirm (id, index) {
      this.$refs['popover-' + index].doClose()
      this.deleteData(id)
    },
    resetTemp () {
      this.temp = {
        id: undefined,
        name: '',
        sort: 1,
        pid: 0
      }
    },
    handleCreate () {
      this.resetTemp()
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    createData () {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          this.dialogLoading = true
          createCategory(this.temp).then(() => {
            this.dialogLoading = false
            this.dialogFormVisible = false
            this.$message({
              message: 'Successfully added.',
              type: 'success'
            })
            this.fetchData()
          })
        }
      })
    },
    handleUpdate (row) {
      this.temp = Object.assign({}, row)
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    updateData () {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          this.dialogLoading = true
          const tempData = Object.assign({}, this.temp)
          updateCategory(tempData).then(() => {
            this.dialogLoading = false
            this.dialogFormVisible = false
            this.$message({
              message: 'Successfully updated.',
              type: 'success'
            })
            this.fetchData()
          })
        }
      })
    },
    handleSelectionChange (val) {
      this.multipleSelection = val
    },
    handleDeleteMuti () {
      if (this.multipleSelection.length < 1) {
        this.$message({
          type: 'warning',
          message: 'Select categories.'
        })
      } else {
        this.$confirm('Multiple categories will be deleted, continue?', 'Hint', {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning'
        }).then(() => {
          const params = {
            ids: []
          }
          this.multipleSelection.map(function (item) {
            params.ids.push(item.id)
          })
          deleteMutiCategory(params).then(() => {
            this.dialogLoading = false
            this.dialogFormVisible = false
            this.$message({
              message: 'Successfully deleted.',
              type: 'success'
            })
            this.fetchData()
          })
        })
      }
    },
    handleSort (val) {
      this.listQuery.order = val.prop
      this.listQuery.sorted = val.order
      this.fetchData()
    }
  }
}
</script>
