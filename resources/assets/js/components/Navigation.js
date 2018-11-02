import React, { Component } from 'react';
import Auth from './Auth'
import { NavLink } from "react-router-dom";
import { withNamespaces } from 'react-i18next';
import i18n from './i18n';

class Navigation extends Component
{
    constructor() {
        super();
        this.auth = new Auth();
        this.login = this.login.bind(this);
        this.logout = this.logout.bind(this);
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
        const t = this.props.t;

        const isAuthenticated = this.auth.isAuthenticated();
        const profile = isAuthenticated ? this.auth.getProfile() : {};

        return (
            <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                <button className="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"/>
                </button>
                <div className="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul className="navbar-nav mr-auto">
                        <NavLink to="/courses" className="nav-link" activeClassName="active" exact={true}>{t('titles:courses')}</NavLink>
                        <NavLink to="/membership" className="nav-link" activeClassName="active">{t('titles:membership')}</NavLink>
                        <NavLink to="/users" className="nav-link" activeClassName="active">{t('titles:users')}</NavLink>
                    </ul>
                    <ul className="navbar-nav">
                        <li className="nav-item dropdown">
                            <a className="nav-link dropdown-toggle" href="#" id="languageDropdown"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {t('common:language:'+i18n.language)}
                            </a>
                            <div className="dropdown-menu dropdown-menu-right">
                                <a href="#" className={"dropdown-item" + (i18n.language === "da" ? " active" : "")} onClick={() => i18n.changeLanguage('da')}>{t('common:language:da')}</a>
                                <a href="#" className={"dropdown-item" + (i18n.language === "en" ? " active" : "")} onClick={() => i18n.changeLanguage('en')}>{t('common:language:en')}</a>
                            </div>
                        </li>
                        {!isAuthenticated && (
                            <li className="nav-item">
                                <a href="#" className="nav-link" onClick={this.login}>{t('actions:signIn')}</a>
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
                                    <NavLink to="/profile" className="dropdown-item" activeClassName="active">{t('titles:myProfile')}</NavLink>
                                    <a className="dropdown-item" href="#" onClick={this.logout}>{t('actions:signOut')}</a>
                                </div>
                            </li>
                        )}
                    </ul>
                </div>
            </nav>
        );
    }
}

export default withNamespaces()(Navigation);