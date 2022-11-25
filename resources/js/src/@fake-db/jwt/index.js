import mock from '@/@fake-db/mock'
import jwt from 'jsonwebtoken'
import axios from 'axios'

const data = {
    users: [{
            id: 1,
            fullName: 'John Doe',
            name: 'johndoe',
            password: 'admin',
            // eslint-disable-next-line global-require
            avatar: require('@/assets/images/avatars/13-small.png'),
            email: 'admin@demo.com',
            role: 'admin',
            ability: [{
                action: 'manage',
                subject: 'all',
            }, ],
            extras: {
                eCommerceCartItemsCount: 5,
            },
        },
        {
            id: 2,
            fullName: 'Jane Doe',
            name: 'janedoe',
            password: 'client',
            // eslint-disable-next-line global-require
            avatar: require('@/assets/images/avatars/1-small.png'),
            email: 'client@demo.com',
            role: 'client',
            ability: [{
                    action: 'read',
                    subject: 'ACL',
                },
                {
                    action: 'read',
                    subject: 'Auth',
                },
            ],
            extras: {
                eCommerceCartItemsCount: 5,
            },
        },
    ],
}

// ! These two secrets shall be in .env file and not in any other file
const jwtConfig = {
    secret: 'dd5f3089-40c3-403d-af14-d0c228b05cb4',
    refreshTokenSecret: '7c4c1c50-3230-45bf-9eae-c9b2e401c767',
    expireTime: '100m',
    refreshTokenExpireTime: '100m',
}

mock.onPost('/jwt/login').reply(async request => {
    const { email, password } = JSON.parse(request.data)

    let error = {
        email: ['Something went wrong'],
    }

    const options = {
        headers: { 'Content-Type': 'application/json' }
    };
    const res = await axios.post('/api/login', request.data, options);
    if (res.data.status == "success") {
        const user = res.data.user;
        user.ability = [{
            action: 'manage',
            subject: 'all',
        }, ];

        user.avatar = require('@/assets/images/avatars/13-small.png'),
            user.role = 'admin',
            user.extras = {
                eCommerceCartItemsCount: 5,
            };
        try {
            const accessToken = res.data.authorisation.token
            const refreshToken = jwt.sign({ id: user.id }, jwtConfig.refreshTokenSecret, {
                expiresIn: jwtConfig.refreshTokenExpireTime,
            })

            const userData = {...user }

            delete userData.password

            const response = {
                userData,
                accessToken,
                refreshToken,
            }

            return [200, response]
        } catch (e) {
            error = e
        }
    } else {
        error = {
            email: ['Email or Password is Invalid'],
        }
    }
    return [400, { error }]
})

mock.onPost('/jwt/register').reply(async request => {
    const { name, email, password } = JSON.parse(request.data)
        // If not any of data is missing return 400
    if (!(name && email && password)) return [400]

    const options = {
        headers: { 'Content-Type': 'application/json' }
    };

    const res = await axios.post('/api/register', request.data, options);

    if (res.data.status == "success") {
        const user = res.data.user;
        user.ability = [{
            action: 'manage',
            subject: 'all',
        }, ];

        user.avatar = require('@/assets/images/avatars/13-small.png'),
            user.role = 'admin',
            user.extras = {
                eCommerceCartItemsCount: 5,
            };
        try {
            const accessToken = res.data.authorisation.token
            const refreshToken = jwt.sign({ id: user.id }, jwtConfig.refreshTokenSecret, {
                expiresIn: jwtConfig.refreshTokenExpireTime,
            })

            const userData = {...user }

            delete userData.password

            const response = {
                userData,
                accessToken,
                refreshToken,
            }

            return [200, response]
        } catch (e) {
            error = e
        }
    } else
        return [res.data.message];
})

mock.onPost('/jwt/refresh-token').reply(request => {
    const { refreshToken } = JSON.parse(request.data)

    try {
        const { id } = jwt.verify(refreshToken, jwtConfig.refreshTokenSecret)

        const userData = {...data.users.find(user => user.id === id) }

        const newAccessToken = jwt.sign({ id: userData.id }, jwtConfig.secret, { expiresIn: jwtConfig.expiresIn })
        const newRefreshToken = jwt.sign({ id: userData.id }, jwtConfig.refreshTokenSecret, {
            expiresIn: jwtConfig.refreshTokenExpireTime,
        })

        delete userData.password
        const response = {
            userData,
            accessToken: newAccessToken,
            refreshToken: newRefreshToken,
        }

        return [200, response]
    } catch (e) {
        const error = 'Invalid refresh token'
        return [401, { error }]
    }
})