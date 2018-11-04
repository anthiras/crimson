import React, { Component } from 'react';
import CourseCard from './CourseCard'
import Auth from "./Auth";
import { get } from './Api';
import { Loading } from './Utilities';

class CourseList extends Component
{
    constructor(props) {
        super(props);
        this.auth = new Auth();
        this.state = {
            courses: [],
            loading: false
        }
    }

    componentDidMount() {
        this.setState( { loading: true });
        get('/api/courses?include[]=instructors').then(courses => {
            this.setState({ courses, loading: false });
        });
    }

    render() {
        if (this.state.loading) return <Loading />;
        return (
            <div className="card-columns">
                {this.state.courses.map(course =>
                    <CourseCard course={course} key={course.id} />
                )}
            </div>
        );
    }
}

export default CourseList;