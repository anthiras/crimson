import React, { Component } from 'react';
import { post } from './Api';

class AddCourse extends Component {
	constructor(props) {
		super(props);
		this.state = {
			newCourse: {
				name: 'New course',
				startsAtDate: '2018-01-01',
				startsAtTime: '08:00:00',
				weeks: 8,
				durationMinutes: 60
			}
		}

		this.handleSubmit = this.handleSubmit.bind(this);
		this.handleInput = this.handleInput.bind(this);
	}

	handleInput(key, e) {
		var state = Object.assign({}, this.state.newCourse); 
    	state[key] = e.target.value;
    	this.setState({newCourse: state });
	}

	handleSubmit(e) {
		e.preventDefault();

		let data = {
			name: this.state.newCourse.name,
			startsAt: this.state.newCourse.startsAtDate + " " + this.state.newCourse.startsAtTime,
			weeks: this.state.newCourse.weeks,
			durationMinutes: this.state.newCourse.durationMinutes
		};

        console.log(data);

        post('/api/courses', data)
            .then(() => { location.href='/'; });
	}

	render() {
		return (
			<div className="row">
				<div className="col-md-4">
					<form onSubmit={this.handleSubmit}>
						<div className="form-group">
							<label htmlFor="name">Name</label>
							<input type="text" required id="name" className="form-control" value={this.state.newCourse.name} onChange={(e)=>this.handleInput('name', e)} />
						</div>
						<div className="form-group">
							<label htmlFor="startsAt">Start date</label>
							<input type="date" required id="startsAtDate" className="form-control" value={this.state.newCourse.startsAtDate} onChange={(e)=>this.handleInput('startsAtDate', e)} />
						</div>
                        <div className="form-group">
                            <label htmlFor="startsAt">Time</label>
                            <input type="time" required id="startsAtTime" className="form-control" value={this.state.newCourse.startsAtTime} onChange={(e)=>this.handleInput('startsAtTime', e)} />
                        </div>
						<div className="form-group">
							<label htmlFor="weeks">Weeks</label>
							<input type="number" required id="weeks" className="form-control"  value={this.state.newCourse.weeks} onChange={(e)=>this.handleInput('weeks', e)} />
						</div>
						<div className="form-group">
							<label htmlFor="duration">Duration of lessons (minutes)</label>
							<input type="number" required id="duration" className="form-control" value={this.state.newCourse.durationMinutes} onChange={(e)=>this.handleInput('durationMinutes', e)} />
						</div>
						<button type="submit" className="btn btn-primary">Save course</button>
					</form>
				</div>
			</div>
			);
	}
}

export default AddCourse;