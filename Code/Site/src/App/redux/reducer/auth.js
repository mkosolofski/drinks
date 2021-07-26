import immutable from 'immutable';
import defaultState from '../store/state/auth'
import * as AuthAction from '../action/auth';

const defaultStateImmutable = immutable.fromJS(defaultState);

const AuthReducer = (state = defaultStateImmutable, action) => {
    switch (action.type) {
        case AuthAction.ACTION_TYPE_LOGIN:
            return state.set('token', action.token).set('username', action.username);

        default:
            return state;
    }
}

export default AuthReducer;
