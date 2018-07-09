import React, { Component } from 'react';
import Auth from './Auth'
import { NavLink } from "react-router-dom";

class Navigation extends Component
{
    constructor() {
        super();
        this.auth = new Auth();
        this.login = this.login.bind(this);
        this.logout = this.logout.bind(this);
    }

    componentDidMount() {

    }

    login(e) {
        e.preventDefault();
        this.auth.login();
    }

    logout(e) {
        e.preventDefault();
        this.auth.logout();
    }

    render() {
        const isAuthenticated = this.auth.isAuthenticated();
        if (isAuthenticated) console.log(this.auth.getProfile());
        const profile = isAuthenticated ? this.auth.getProfile() : {};

        return (
            <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                <button className="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                </button>
                <div className="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul className="navbar-nav mr-auto">
                        <NavLink to="/" className="nav-link" activeClassName="active" exact={true}>Courses</NavLink>
                        <NavLink to="/users" className="nav-link" activeClassName="active">Users</NavLink>
                    </ul>
                    <ul className="navbar-nav">
                        {!isAuthenticated && (
                            <li className="nav-item">
                                <a href="#" className="nav-link" onClick={this.login}>Sign in</a>
                            </li>
                        )}
                        {isAuthenticated && (
                            <li className="nav-item dropdown">
                                <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src={profile.picture} width="20" height="20" className="align-middle mr-1"/>
                                    {profile.name}
                                </a>
                                <div className="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                    <a className="dropdown-item" href="#" onClick={this.logout}>Sign Out</a>
                                </div>
                            </li>
                        )}
                    </ul>
                </div>
            </nav>
        );
    }
}

export default Navigation;