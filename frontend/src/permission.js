import router from '@/router'
import store from '@/store'
import { Message } from 'element-ui'
import NProgress from 'nprogress' // progress bar
import 'nprogress/nprogress.css' // progress bar style
import { getToken } from '@/utils/auth' // get token from cookie
import getPageTitle from '@/utils/get-page-title'

NProgress.configure({ showSpinner: false }) // NProgress Configuration

/**
 *  Change the orginal whiteList method of route authrization to be configured by each route's meta definiton with
 *      {
 *         path: '/login',
 *         name: 'login',
 *         component: () => import('@/views/login'),
 *         meta: {
 *             title: 'login',
 *             whitelist: true
 *       },
 *       hidden: true
 *     },
*/
// no redirect whitelist
const whiteList = [
  // '/',
  // '/login'
]

router.beforeEach(async(to, from, next) => {
  // start progress bar
  NProgress.start()

  // set page title
  document.title = getPageTitle(to.meta.title)

  // determine whether the user has logged in
  const hasToken = getToken()

  if (hasToken) {
    if (to.path === '/login') {
      // if is logged in, redirect to the home page
      next({ path: '/backend' })
      NProgress.done()
    } else {
      const hasGetUserInfo = store.getters.name
      if (hasGetUserInfo) {
        next()
      } else {
        try {
          // get user info
          const userInformation = await store.dispatch('user/getInfo')

          //backend returns administrator role 或者 returns normal users' permissions.
          const permissions = userInformation.result.data.roles.data[0].name === 'superadmin' ? ['superadmin'] : userInformation.result.data.roles.data[0].permissions.data.map(item => item.name)

          const accessRoute = await store.dispatch('permission/generateRoutes', permissions)

          router.addRoutes(accessRoute)

          next({ ...to, replace: true }) //确保addRoutes已完成, next不再调用push()，而是调用route.replace(...to, true); 第二个参数true表示设置路由完成
        }
        catch (error) {
          // remove token and go to login page to re-login
          await store.dispatch('user/resetToken')
          Message.error(error || 'Has Error')
          next(`/login?redirect=${to.path}`)
          NProgress.done()
        }
      }
    }
  } else {
    /* has no token*/

    // if (whiteList.indexOf(to.path) !== -1 ) {
    // if ( regWhiteList.some(regPattern => regPattern.test(to.path))  ) {
      // in the free login whitelist, go directly
    if (whiteList.indexOf(to.path) !== -1
      // || !!to.meta.whitelist
    ) {
      next()
    } else {
      // other pages that do not have permission to access are redirected to the login page.
      next(`/login?redirect=${to.path}`)
      NProgress.done()
    }
  }
})

router.afterEach(() => {
  // finish progress bar
  NProgress.done()
})
