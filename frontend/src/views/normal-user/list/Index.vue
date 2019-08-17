<template>
  <div class="app-container">
    <select-filter :list-query="listQuery" />
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
            <p class="el-icon-warning">Confirm deletion</p>
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
import Pagination from '@/components/Pagination'
import SelectFilter from './Filter'
import { getUserList } from '@/api/admin-user/user'

export default {
  components: {
    Pagination,
    SelectFilter
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
        0: 'disabled',
        1: 'apply'
      },
      listLoading: true, // 列表数据加载
      total: 0,
      listQuery: { // 分页 及过滤筛选条件
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
      console.log('get list!')
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
      // console.log('路由跳转--- create')
      this.$router.push('/user/add')
    },
    handleFilter () {
      // console.log('筛选过滤')
      this.listQuery.page = 1
      this.fetchData()
    },
    handleUpdate (item) {
      // console.log(`edit item.id = ${item.id} 列表元素`)
      this.$router.push(`/user/edit/${item.id}`)
    },
    doConfirm (id, index) {
      // console.log('删除')
      // deleteUser(id).then((res) => {
      //   this.$refs['popover-' + index].doClose()
      //   this.list = this.list.filter(item => item.id !== id)
      //   this.total--
      //   this.$message({ type: 'success', message: '删除成功' })
      // })
    },
    handleSort (val) {
      // console.log('排序列表')
      this.listQuery.order = val.prop
      this.listQuery.sorted = val.order
      this.fetchData()
    }
  }
}
</script>
