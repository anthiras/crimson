import React, { Component } from 'react';
import { NavLink } from "react-router-dom";

class CourseNavigation extends Component
{
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <ul className="nav nav-pills my-3">
                <li className="nav-item">
                    <NavLink to="/" className="nav-link" activeClassName="active" exact={true}>Courses</NavLink>
                </li>
                <li className="nav-item">
                    <NavLink  to="/createcourse" className="nav-link" activeClassName="active"><span className="oi oi-plus"></span> Create new course</NavLink>
                </li>
            </ul>
        );
    }
}

export default CourseNavigation;