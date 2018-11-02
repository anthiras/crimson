import React from 'react';
import { NavLink } from "react-router-dom";
import { withNamespaces } from 'react-i18next';

const CourseNavigation = ({ t }) => (
    <div className="row">
        <div className="col-sm">
            <ul className="nav nav-pills my-3">
                <li className="nav-item">
                    <NavLink to="/courses" className="nav-link" activeClassName="active" exact={true}>{t('titles:courses')}</NavLink>
                </li>
                <li className="nav-item">
                    <NavLink  to="/courses/create" className="nav-link" activeClassName="active" exact={true}>
                        <span className="oi oi-plus"/> {t('courses:createNew')}
                    </NavLink>
                </li>
            </ul>
        </div>
    </div>
);

export default withNamespaces()(CourseNavigation);