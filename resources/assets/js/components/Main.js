import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import AddCourse from './AddCourse'
import CourseList from './CourseList'
import CourseNavigation from './CourseNavigation'
import UserList from './UserList'
import Navigation from './Navigation'
import { Router, Route, Link } from "react-router-dom";
import Auth from './Auth'
import history from './History';

const auth = new Auth();

const handleAuthentication = (nextState, replace) => {
    if (/access_token|id_token|error/.test(nextState.location.hash)) {
        auth.handleAuthentication();
    }
}

const App = () => (
    <div>
        <Navigation/>
        <div className="container">
            <div className="row">
                <div className="col-sm">
                    <CourseNavigation/>
                </div>
            </div>
            <Route exact path="/" component={CourseList} />
            <Route path="/createcourse" component={AddCourse} />
            <Route path="/users" component={UserList} />
            <Route path="/callback" render={(props) => {
                handleAuthentication(props);
                return (<div>Loading</div>);
            }}/>
        </div>
    </div>
);

const AppRouter = () => (
    <Router history={history}>
        <App />
    </Router>
)

ReactDOM.render(<AppRouter/>, document.getElementById('root'))