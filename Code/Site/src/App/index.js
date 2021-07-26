import React from 'react';
import { BrowserRouter, Switch, Route } from 'react-router-dom'
import { Provider } from 'react-redux'
import Store from './redux/store'
import LayoutComponent from './container/layout';
import Home from './container/page/home';
import PrivateRoute from './container/private-route';

let store = Store();

export const CurrentStore = store;

function App() {
    return <Provider store={store}>
        <BrowserRouter>
            <Switch>
                {/*
                <Route path="/" component={Login} />
                */}
                <LayoutComponent>
                    <PrivateRoute exact path="/" component={Home} />
                </LayoutComponent>
            </Switch>
        </BrowserRouter>
    </Provider>
}

export default App;
