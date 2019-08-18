<template>
  <div class="app-container">
    <article-filter :list-query="listQuery" />
    <el-row>
      <el-col :span="24">
        <el-button-group>
          <el-button
            v-if="checkPermission(['admin-create-article'])"
            type="primary"
            @click="handleCreate"
          >
            ADD
          </el-button>
          <el-button
            v-if="checkPermission(['admin-delete-muti-article'])"
            type="danger"
            @click="handleDeleteMuti"
          >
            Batch deletion
          </el-button>
        </el-button-group>
      </el-col>
    </el-row>
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      border
      fit
      highlight-current-row
      style="margin-top:10px"
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
        label="编号"
        sortable="custom"
        width="85"
      >
        <template slot-scope="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column
        label="Title"
        prop="name"
        width="250"
      >
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column
        label="Author"
        prop="email"
        width="110"
      >
        <template slot-scope="scope">
          {{ scope.row.author }}
        </template>
      </el-table-column>
      <el-table-column
        label="Category"
        prop="category_id"
        sortable="custom"
        width="110"
      >
        <template slot-scope="scope">
          {{ scope.row.category.data.name }}
        </template>
      </el-table-column>
      <el-table-column
        label="Origin"
        prop="source"
        width="110"
      >
        <template slot-scope="scope">
          {{ scope.row.source }}
        </template>
      </el-table-column>
      <el-table-column
        label="Hits"
        prop="views"
        width="90"
        sortable="custom"
      >
        <template slot-scope="scope">
          {{ scope.row.views }}
        </template>
      </el-table-column>
      <el-table-column
        label="Status"
        prop="status"
        width="90"
        sortable="custom"
      >
        <template slot-scope="scope">
          <el-tag :type="scope.row.status | statusFilter">{{ confirmedMap[scope.row.status] }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column
        label="Created at"
        prop="created_at"
        width="100"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column
        v-if="checkPermission(['admin-update-article']) || checkPermission(['admin-delete-article'])"
        label="Operations"
        align="center"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="scope">
          <el-button
            v-if="checkPermission(['admin-update-article'])"
            type="primary"
            @click="handleUpdate(scope.row)"
          >
            Edit
          </el-button>

          <el-popover
            :ref="`popover-${scope.$index}`"
            trigger="click"
            placement="top"
            width="100"
          >
            <p class="el-icon-warning">确定删除</p>
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
              v-if="checkPermission(['admin-delete-article'])"
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
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'
import ArticleFilter from './filter'
import { getList, deleteArticle, deleteMutiArticle } from '@/api/article/article'
import { checkPermission } from '@/utils/permission'

export default {
  components: {
    Pagination,
    ArticleFilter
  },
  filters: {
    statusFilter (status) {
      const statusMap = {
        1: 'success',
        0: 'danger'
      }
      return statusMap[status]
    }
  },
  data () {
    return {
      list: null,
      multipleSelection: [],
      confirmedMap: {
        0: 'Draft',
        1: 'Published'
      },
      listLoading: true,
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
        order: '',
        sorted: '',
        name: null,
        author: null,
        source: null,
        status: null,
        category_id: null
      }
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    checkPermission,
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
    handleCreate () {
      this.$router.push('/article/list/create')
    },
    handleFilter () {
      this.listQuery.page = 1
      this.fetchData()
    },
    handleUpdate (item) {
      this.$router.push(`/article/list/edit/${item.id}`)
    },
    doConfirm (id, index) {
      deleteArticle(id).then((res) => {
        this.$refs['popover-' + index].doClose()
        this.list = this.list.filter(item => item.id !== id)
        this.total--
        this.$message({ type: 'success', message: 'Successfully deleted.' })
      })
    },
    handleSelectionChange (val) {
      this.multipleSelection = val
    },
    handleDeleteMuti () {
      if (this.multipleSelection.length < 1) {
        this.$message({
          type: 'warning',
          message: 'Please select.'
        })
      } else {
        this.$confirm('Multiple posts will be deleted, continue?', 'Hint', {
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
          deleteMutiArticle(params).then(() => {
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
