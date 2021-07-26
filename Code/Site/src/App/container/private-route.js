import React from 'react';
import { connect } from 'react-redux';
import { Redirect } from 'react-router-dom';
import { Route } from 'react-router';

const PrivateRoute = ({ component: Component, userToken, updateApplicationProperty, ...rest }) => {
    return <Route
        {...rest}
        render={
            (props) => {
                if (userToken) {
                    let component = <Component {...props} />;
                    return component;
                }

                return <Redirect to='/login' />
            }
        }
    />
};

const mapStateToProps = (state) => {
   return {
      userToken: state.auth.get('token')
   }
}

const mapDispatchToProps = (dispatch) => {
    return {
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(PrivateRoute)
