import axios from "axios";
import Cookies from "js-cookie";
import useJwt from "@/auth/jwt/useJwt";

// window.apiUrl = 'https://channelable.shibbir.dev/api/'
// if (window.location.origin === 'https://channelable.shibbir.dev/') {
//     window.apiUrl = 'https://channelable.shibbir.dev/api/'
// } else if (window.location.origin === 'http://localhost:3000') {
//     window.apiUrl = 'http://localhost:3000/api/'
// } else {
//     window.apiUrl = 'https://channelable.shibbir.dev/api/'
// }


// if (window.location.origin === 'https://channelable.shibbir.dev') {
//     window.apiUrl = 'https://channelable.shibbir.dev/api/';
//     window.baseUrl = "https://channelable.shibbir.dev/";
// } else if (window.location.origin === 'http://localhost:3000') {
//     window.apiUrl = 'http://localhost:3000/api/';
//     window.baseUrl = "http://localhost:3000/";
// } else {
//     window.apiUrl = "http://localhost:8000/api/";
//     window.baseUrl = "http://localhost:8080/";
// }

window.apiUrl = window.location.origin + '/api/';
window.baseUrl = window.location.origin + "/";

let header = {
    Authorization: `Bearer ${useJwt.getToken()}`,
};
axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        const { status } = error.response;
        const config = error.config;
        if (status === 401) {
            axios
                .post(
                    window.apiUrl + "refresh",
                    {},
                    {
                        headers: header,
                    }
                )
                .then((token) => {
                    useJwt.setToken(token.data.authorisation.token);
                    config.headers[
                        "Authorization"
                    ] = `Bearer ${useJwt.getToken()}`;
                    return axios(config);
                });
        }
        return Promise.reject(error);
    }
);


export const http = {
    get: (url) => {
        return new Promise((resolve, reject) => {
            let header = {
                'Authorization': `Bearer ${useJwt.getToken()}`
            };
            axios.get(window.apiUrl + url, { headers: header }).then(response => {
                resolve(response);
            }).catch(e => {
                reject(e)
            });
        })
    },
    post: (url, data = {}) => {
        return new Promise((resolve, reject) => {
            let header = {
                'Authorization': `Bearer ${useJwt.getToken()}`
            };
            axios.post(window.apiUrl + url, data, { headers: header }).then(response => {
                resolve(response);
            }).catch(e => {
                reject(e)
            });
        })
    },
    put: (url, data = {}) => {
        return new Promise((resolve, reject) => {
            let header = {
                'Authorization': `Bearer ${useJwt.getToken()}`
            };
            axios.put(window.apiUrl + url, data, { headers: header }).then(response => {
                resolve(response);
            }).catch(e => {
                reject(e)
            });
        })
    },
    delete: (url) => {
        return new Promise((resolve, reject) => {
            let header = {
                'Authorization': `Bearer ${useJwt.getToken()}`
            };
            axios.delete(window.apiUrl + url, { headers: header }).then(response => {
                resolve(response);
            }).catch(e => {
                reject(e)
            });
        })
    },
    file: (url, data = {}) => {
        return new Promise((resolve, reject) => {
            let header = {
                'Authorization': `Bearer ${useJwt.getToken()}`,
                'Content-Type': 'multipart/form-data'
            };
            axios.post(apiUrl + url, data, { headers: header }).then(response => {
                resolve(response);
            }).catch(e => {
                reject(e)
            });
        })
    },
    postWithoutToken: (url, data = {}) => {
        return new Promise((resolve, reject) => {
            axios.post(window.apiUrl + url, data).then(response => {
                resolve(response);
            }).catch(e => {
                reject(e)
            });
        })
    }
};