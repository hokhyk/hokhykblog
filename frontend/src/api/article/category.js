import request from '@/utils/request'

export function getList(params) {
  return request({
    url: '/article_categories',
    method: 'get',
    params
  })
}

export function getAllList(params) {
  return request({
    url: '/all_article_categories',
    method: 'get',
    params
  })
}

export function deleteCategory(id) {
  return request({
    url: '/article_categories/' + id,
    method: 'delete'
  })
}

export function deleteMutiCategory(params) {
  return request({
    url: '/article_categories/muti',
    method: 'post',
    params
  })
}

export function createCategory(params) {
  return request({
    url: '/article_categories',
    method: 'post',
    params
  })
}

export function updateCategory(params) {
  return request({
    url: '/article_categories/' + params.id,
    method: 'patch',
    params
  })
}
