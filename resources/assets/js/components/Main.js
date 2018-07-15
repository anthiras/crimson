import React from 'react';
import ReactDOM from 'react-dom';
import AddCourse from './AddCourse'
import CourseList from './CourseList'
import CourseNavigation from './CourseNavigation'
import UserList from './UserList'
import Navigation from './Navigation'
import { Router, Route, Redirect, Switch } from "react-router-dom";
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
            <Switch>
                <Route exact path="/" >
                    <Redirect to="/courses" />
                </Route>
                <Route path="/courses">
                    <React.Fragment>
                        <CourseNavigation/>
                        <Route exact path="/courses" component={CourseList} />
                        <Route exact path="/courses/create" component={AddCourse} />
                    </React.Fragment>
                </Route>
                <Route path="/users" component={UserList} />
                <Route path="/callback" render={(props) => {
                    handleAuthentication(props);
                    return (<div>Loading</div>);
                }}/>
            </Switch>
        </div>
    </div>
);

const AppRouter = () => (
    <Router history={history}>
        <App />
    </Router>
)

ReactDOM.render(<AppRouter/>, document.getElementById('root'))