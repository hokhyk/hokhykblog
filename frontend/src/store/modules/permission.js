import { asyncRoutes, constantRoutes } from '@/router'

/**
 * We define for every route's meta info with permissions array, so by checking
 * route.meta.permissions.includes(permission) or not will tell the router this route is authorized for access.
 * If route does not define meta information or meta information without permissions array defined, by default this route is authorized for any user.
 * @param permissions
 * @param route
 */
function hasPermission (permissions, route) {
  if (route.meta && route.meta.permissions) {
    return permissions.some(permission => route.meta.permissions.includes(permission))
  } else {
    return true
  }
}

/**
 * Filter asynchronous routing tables by recursion
 * @param routes asyncRoutes
 * @param permissions
 */
export function filterAsyncRoutes (routes, permissions) {
  const authorizedRoutes = []

  routes.forEach(route => {
    if (hasPermission(permissions, route)) {
      if (route.children) {
        route.children = filterAsyncRoutes(route.children, permissions)
      }
      authorizedRoutes.push(route)
    }
  })
  return authorizedRoutes
}

const state = {
  routes: [],
  addRoutes: []
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  }
}

const actions = {
  generateRoutes ({ commit }, permissions) {
    return new Promise((resolve, reject) => {
      let authorizedRoutes
      if(!permissions){
        if (permissions.includes('superadmin')) {
          authorizedRoutes = asyncRoutes || []
        } else {
          authorizedRoutes = filterAsyncRoutes(asyncRoutes, permissions)
        }
        commit('SET_ROUTES', authorizedRoutes)
        resolve(authorizedRoutes)
      }
      else {
        reject({message: "Empty parameter: permissions!"})
      }
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
