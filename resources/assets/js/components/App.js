import React from 'react';
import AddCourse from './AddCourse'
import CourseList from './CourseList'
import CourseNavigation from './CourseNavigation'
import CourseDetails from './CourseDetails';
import UserList from './UserList'
import UserProfile from './UserProfile';
import Membership from './Membership';
import Navigation from './Navigation'
import { Router, Route, Redirect, Switch } from "react-router-dom";
import Auth from './Auth'
import history from './History';
import { withNamespaces } from 'react-i18next';

const auth = new Auth();

const App = ({ t }) => (
    <Router history={history}>
        <React.Fragment>
            <Navigation/>
            <div className="container">
                <Switch>
                    <Route exact path="/" >
                        <Redirect to="/courses" />
                    </Route>
                    <Route path="/courses">
                        <React.Fragment>
                            <CourseNavigation/>
                            <Switch>
                                <Route exact path="/courses" component={CourseList} />
                                <Route exact path="/courses/create" component={AddCourse} />
                                <Route exact path="/courses/:courseId" component={CourseDetails} />
                            </Switch>
                        </React.Fragment>
                    </Route>
                    <Route path="/users" component={UserList} />
                    <Route path="/profile" component={UserProfile} />
                    <Route path="/membership" component={Membership} />
                    <Route path="/callback" render={(props) => {
                        auth.handleAuthentication(props);
                        return (<div>Loading</div>);
                    }}/>
                </Switch>
            </div>
        </React.Fragment>
    </Router>
);

export default withNamespaces()(App);
