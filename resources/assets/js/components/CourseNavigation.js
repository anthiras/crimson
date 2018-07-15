import React from 'react';
import { NavLink } from "react-router-dom";

const CourseNavigation = () => (
    <div className="row">
        <div className="col-sm">
            <ul className="nav nav-pills my-3">
                <li className="nav-item">
                    <NavLink to="/courses" className="nav-link" activeClassName="active" exact={true}>Courses</NavLink>
                </li>
                <li className="nav-item">
                    <NavLink  to="/courses/create" className="nav-link" activeClassName="active" exact={true}><span className="oi oi-plus"></span> Create new course</NavLink>
                </li>
            </ul>
        </div>
    </div>
);

export default CourseNavigation;