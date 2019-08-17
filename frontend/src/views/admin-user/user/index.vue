<template>
  <div class="app-container">
    <user-filter :list-query="listQuery" />
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
        width="200"
      >
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column
        label="E-mail"
        prop="email"
        sortable="custom"
      >
        <template slot-scope="scope">
          {{ scope.row.email }}
        </template>
      </el-table-column>
      <el-table-column
        label="Role"
        prop="roles"
        width="150"
      >
        <template slot-scope="scope">
          {{ scope.row.roles.data[0]['display_name'] }}
        </template>
      </el-table-column>
      <el-table-column
        label="Status"
        prop="confirmed"
        width="100"
        sortable="custom"
      >
        <template slot-scope="scope">
          <el-tag :type="scope.row.confirmed | statusFilter">{{ confirmedMap[scope.row.confirmed] }}</el-tag>
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
              :disabled="scope.row.roles.data[0].name === 'superadmin'"
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
import { getUserList, deleteUser } from '@/api/admin-user/user'
import Pagination from '@/components/Pagination'
import UserFilter from './filter'

export default {
  components: {
    Pagination,
    UserFilter
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
      confirmedMap: {
        0: 'Disable',
        1: 'Apply'
      },
      listLoading: true,
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
        order: '',
        sorted: '',
        name: null,
        email: null,
        confirmed: null,
        role_id: null
      }
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      this.listLoading = true
      getUserList(this.listQuery).then(response => {
        const pagination = response.result.meta.pagination
        this.list = response.result.data
        this.total = pagination.total
        this.listQuery.page = pagination.current_page
        this.listQuery.limit = pagination.per_page
        this.listLoading = false
      })
    },
    handleCreate () {
      this.$router.push('/admin-users/user/create')
    },
    handleFilter () {
      this.listQuery.page = 1
      this.fetchData()
    },
    handleUpdate (item) {
      this.$router.push(`/admin-users/user/edit/${item.id}`)
    },
    doConfirm (id, index) {
      deleteUser(id).then((res) => {
        this.$refs['popover-' + index].doClose()
        this.list = this.list.filter(item => item.id !== id)
        this.total--
        this.$message({ type: 'success', message: 'Successfully Deleted.' })
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
