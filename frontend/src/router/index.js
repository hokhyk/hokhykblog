import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout/index'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true
  },

  {
    path: '/',
    component: () => import('@/home/index'),
    redirect: '/backend/dashboard',
    hidden: true
  },

  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },

  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]

export const asyncRoutes = [
  {
    path: '/backend',
    component: Layout,
    redirect: '/backend/dashboard',
    children: [{
      path: 'dashboard',
      name: 'Dashboard',
      component: () => import('@/views/dashboard/index'),
      meta: { title: 'Dashboard', icon: 'dashboard' }
    }]
  },

  {
    path: '/example',
    component: Layout,
    redirect: '/example/table',
    name: 'Example',
    meta: { title: 'Example', icon: 'example' },
    children: [
      {
        path: 'table',
        name: 'Table',
        component: () => import('@/views/table/index'),
        meta: { title: 'Table', icon: 'table' }
      },
      {
        path: 'tree',
        name: 'Tree',
        component: () => import('@/views/tree/index'),
        meta: { title: 'Tree', icon: 'tree' }
      }
    ]
  },

  {
    path: '/form',
    component: Layout,
    children: [
      {
        path: 'index',
        name: 'Form',
        component: () => import('@/views/form/index'),
        meta: { title: 'Form', icon: 'form' }
      }
    ]
  },

  {
    path: '/nested',
    component: Layout,
    redirect: '/nested/menu1',
    name: 'Nested',
    meta: {
      title: 'Nested',
      icon: 'nested'
    },
    children: [
      {
        path: 'menu1',
        component: () => import('@/views/nested/menu1/index'), // Parent router-view
        name: 'Menu1',
        meta: { title: 'Menu1' },
        children: [
          {
            path: 'menu1-1',
            component: () => import('@/views/nested/menu1/menu1-1'),
            name: 'Menu1-1',
            meta: { title: 'Menu1-1' }
          },
          {
            path: 'menu1-2',
            component: () => import('@/views/nested/menu1/menu1-2'),
            name: 'Menu1-2',
            meta: { title: 'Menu1-2' },
            children: [
              {
                path: 'menu1-2-1',
                component: () => import('@/views/nested/menu1/menu1-2/menu1-2-1'),
                name: 'Menu1-2-1',
                meta: { title: 'Menu1-2-1' }
              },
              {
                path: 'menu1-2-2',
                component: () => import('@/views/nested/menu1/menu1-2/menu1-2-2'),
                name: 'Menu1-2-2',
                meta: { title: 'Menu1-2-2' }
              }
            ]
          },
          {
            path: 'menu1-3',
            component: () => import('@/views/nested/menu1/menu1-3'),
            name: 'Menu1-3',
            meta: { title: 'Menu1-3' }
          }
        ]
      },
      {
        path: 'menu2',
        component: () => import('@/views/nested/menu2/index'),
        meta: { title: 'menu2' }
      }
    ]
  },

  {
    path: 'external-link',
    component: Layout,
    children: [
      {
        path: 'https://panjiachen.github.io/vue-element-admin-site/#/',
        meta: { title: 'External Link', icon: 'link' }
      }
    ]
  },

  {
    path: '/user',
    component: Layout,
    redirect: '/user/list',
    name: 'normalUser',
    meta: { title: '个人用户', icon: 'userlist' },
    children: [
      {
        path: 'list',
        name: 'personList',
        component: () => import('@/views/normal-user/list/Index'),
        meta: {
          title: 'User Management'
          // permissions: ['admin-list-all-article-category']
        }
      },
      {
        path: 'add',
        name: 'userAdd',
        component: () => import('@/views/normal-user/list/Add'),
        meta: {
          title: 'Add User'
          // permissions: ['admin-list-all-article-category']
        },
        hidden: true
      },
      {
        path: 'edit/:id',
        name: 'userEdit',
        component: () => import('@/views/normal-user/list/Edit'),
        meta: {
          title: 'Edit User Info'
          // permissions: ['admin-list-all-article-category']
        },
        hidden: true
      }
    ]
  },

  {
    path: '/article',
    component: Layout,
    redirect: '/article/list',
    name: 'Article',
    meta: { title: 'Posts', icon: 'news', permissions: ['admin-list-all-article-category'] },
    children: [
      {
        path: 'list',
        name: 'ArticleList',
        component: () => import('@/views/article/list/index'),
        meta: {
          title: 'Posts',
          permissions: ['admin-list-all-article']
        }
      },
      {
        path: '/article/list/create',
        name: 'CreateArticle',
        component: () => import('@/views/article/list/create'),
        meta: {
          title: 'Add Post',
          permissions: ['admin-create-article']
        },
        hidden: true
      },
      {
        path: '/article/list/edit/:id',
        name: 'EditArticle',
        component: () => import('@/views/article/list/edit'),
        meta: {
          title: 'Edit Post',
          permissions: ['admin-update-article']
        },
        hidden: true
      },
      {
        path: 'category',
        name: 'ArticleCategory',
        component: () => import('@/views/article/category/index'),
        meta: {
          title: 'Categories',
          permissions: ['admin-list-all-article-category']
        }
      }
    ]
  },

  {
    path: '/admin-user',
    component: Layout,
    redirect: '/admin-user/role',
    name: 'AdminUser',
    meta: { title: 'Admin User', icon: 'administrator', permissions: ['admin-create-role'] },
    children: [
      {
        path: 'role',
        name: 'Role',
        component: () => import('@/views/admin-user/role/index'),
        meta: {
          title: 'Roles',
          permissions: ['admin-create-role']
        }
      },
      {
        path: '/admin-user/role/create',
        name: 'CreateRole',
        component: () => import('@/views/admin-user/role/create'),
        meta: {
          title: 'Add Role',
          permissions: ['admin-create-role']
        },
        hidden: true
      },
      {
        path: '/admin-user/role/edit/:id',
        name: 'EditRole',
        component: () => import('@/views/admin-user/role/edit'),
        meta: {
          title: 'Edit Role',
          permissions: ['admin-create-role']
        },
        hidden: true
      },
      {
        path: '/admin-users',
        name: 'AdminUser',
        component: () => import('@/views/admin-user/user/index'),
        meta: { title: 'Admin Users' }
      },
      {
        path: '/admin-users/user/create',
        name: 'CreateUser',
        component: () => import('@/views/admin-user/user/userAdd'),
        meta: {
          title: '添加角色',
          permissions: ['admin-create-role']
        },
        hidden: true
      },
      {
        path: '/admin-users/user/edit/:id',
        name: 'EditUser',
        component: () => import('@/views/admin-user/user/userEdit'),
        meta: {
          title: 'Edit Role',
          permissions: ['admin-create-role']
        },
        hidden: true
      }
    ]
  },

  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },

  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
