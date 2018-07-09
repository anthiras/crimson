import React, { Component } from 'react';
import CourseCard from './CourseCard'
import Auth from "./Auth";
import { get } from './Api';

class CourseList extends Component
{
    constructor(props) {
        super(props);
        this.auth = new Auth();
        this.state = {
            courses: [],
        }
    }

    componentDidMount() {
        get('/api/courses?include[]=instructors').then(courses => {
            this.setState({ courses });
        });
    }

    render() {
        return (
            <div className="card-columns">
                {this.state.courses.map(course => {
                    return (
                        <CourseCard course={course} key={course.id} />
                    );
                })}
            </div>
        );
    }
}

export default CourseList;