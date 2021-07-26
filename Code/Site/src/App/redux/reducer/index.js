import { combineReducers } from 'redux'
import * as AuthAction from '../action/auth';
import AuthReducer from './auth'

const appReducer = combineReducers(
    {
        auth: AuthReducer
    }
)

const rootReducer = (state, action) => {
    return appReducer(action.type === AuthAction.ACTION_TYPE_LOGOUT ? undefined : state, action)
}

export default rootReducer;
