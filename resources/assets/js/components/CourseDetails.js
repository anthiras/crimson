import React, {Component} from 'react';
import moment from 'moment';
import {get} from "./Api";
import ParticipantList from "./ParticipantList";

class CourseDetails extends Component
{
    constructor(props) {
        super(props);
        this.courseId = props.match.params.courseId;
        this.state = {
            course: null
        }
    }

    componentDidMount() {
        get('/api/courses/'+this.courseId).then(course => {
            this.setState({ course });
        });
    }

    render() {
        if (this.state.course == null) {
            return "Loading...";
        }
        const {
            name,
            instructors,
            startsAt,
            durationMinutes,
            participants
        } = this.state.course;

        const courseStartsAt = moment(startsAt);
        let firstLessonEndsAt = courseStartsAt.clone();
        firstLessonEndsAt.add(durationMinutes, 'm');

        return (
            <React.Fragment>
                <h1>{name}</h1>
                <p>{ instructors.map(instructor => instructor.name).join(" & ") }</p>
                <p>{ courseStartsAt.format("dddd") }s { courseStartsAt.format("HH:mm") }–{ firstLessonEndsAt.format("HH:mm") } from { courseStartsAt.format("MMM D") }</p>
                <h2>Participants</h2>
                <ParticipantList participants={participants} />
            </React.Fragment>
        );
    }
}

export default CourseDetails;