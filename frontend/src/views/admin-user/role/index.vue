<template>
  <div class="app-container">
    <role-filter :list-query="listQuery" />
    <br>
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      border
      fit
      highlight-current-row
      @sort-change="handleSort"
    >
      <el-table-column
        align="center"
        prop="id"
        label="编号"
        sortable="custom"
        width="95"
      >
        <template slot-scope="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column
        label="ID"
        prop="name"
        sortable="custom"
        width="200"
      >
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column
        label="Name"
        prop="display_name"
        sortable="custom"
      >
        <template slot-scope="scope">
          {{ scope.row.display_name }}
        </template>
      </el-table-column>
      <el-table-column
        label="Description"
        prop="description"
      >
        <template slot-scope="scope">
          {{ scope.row.description }}
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
        label="Operations"
        align="center"
        width="150"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="scope">
          <el-button
            type="primary"
            :disabled="scope.row.name === 'superadmin'"
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
            <p class="el-icon-warning">Confirm Deletion</p>
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
              :disabled="scope.row.name === 'superadmin'"
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
import { getList, deleteRoleInfo } from '@/api/admin-user/role'
import Pagination from '@/components/Pagination'
import RoleFilter from './filter'

export default {
  components: {
    Pagination,
    RoleFilter
  },
  data () {
    return {
      list: null,
      listLoading: true,
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
        name: undefined,
        order: '',
        sorted: ''
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
    handleCreate () {
      this.$router.push('/admin-user/role/create')
    },
    handleFilter () {
      if (!this.listQuery.name) {
        return false
      }
      this.listQuery.page = 1
      this.fetchData()
    },
    handleUpdate (item) {
      this.$router.push(`/admin-user/role/edit/${item.id}`)
    },
    doConfirm (id, index) {
      deleteRoleInfo(id).then((res) => {
        this.$refs['popover-' + index].doClose()
        this.list = this.list.filter(item => item.id !== id)
        this.total--
        this.$message({ type: 'success', message: 'Successfully deleted.' })
      })
    },
    handleSort (val) {
      this.listQuery.order = val.prop
      this.listQuery.sorted = val.order
      this.fetchData()
    }
  }
}
</script>
