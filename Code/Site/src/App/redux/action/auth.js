export const ACTION_TYPE_LOGIN = Symbol('ACTION_TYPE_LOGIN');
export const ACTION_TYPE_LOGOUT = Symbol('ACTION_TYPE_LOGOUT');

export const Actions = {
    login: (token, username) => {
        return {
            type: ACTION_TYPE_LOGIN,
            token: token,
            username: username
        };
    },
    logout: () => {
        return {
            type: ACTION_TYPE_LOGOUT
        };
    }
}
