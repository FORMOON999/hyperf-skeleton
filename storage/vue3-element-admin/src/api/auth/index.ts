import request from "@/utils/request";
import { AxiosPromise } from "axios";
import {LoginData, LoginResult } from "./types";

/**
 * 登录API
 *
 * @param data {LoginData}
 * @returns
 */
export function loginApi(data: LoginData): AxiosPromise<LoginResult> {
  return request({
    url: "/api/v1/platform/login",
    method: "post",
    data: data,
  });
}

/**
 * 注销API
 */
export function logoutApi() {
  return request({
    url: "/api/v1/platform/logout",
    method: "post",
  });
}

/**
 * 刷新API
 */
export function refreshTokenApi() {
  return request({
    url: "/api/v1/platform/refreshToken",
    method: "post",
  });
}
