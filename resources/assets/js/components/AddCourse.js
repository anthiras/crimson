import React, { Component } from 'react';
import { post } from './Api';

class AddCourse extends Component {
	constructor(props) {
		super(props);
		this.state = {
			newCourse: {
				name: 'New course',
				startsAt: '2018-01-01 08:00:00',
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
		console.log(this.state.newCourse);

        post('api/courses', JSON.stringify(this.state.newCourse))
            .then(() => { location.href='/'; });

		//this.props.onAdd(this.state.newCourse);
	}

	render() {
		return (
			<div className="row">
				<div className="col-md-4">
					<form onSubmit={this.handleSubmit}>
						<div className="form-group">
							<label htmlFor="name">Name</label>
							<input type="text" id="name" className="form-control" value={this.state.newCourse.name} onChange={(e)=>this.handleInput('name', e)} />
						</div>
						<div className="form-group">
							<label htmlFor="startsAt">Start date</label>
							<input type="text" id="startsAt" className="form-control" value={this.state.newCourse.startsAt} onChange={(e)=>this.handleInput('startsAt', e)} />
						</div>
						<div className="form-group">
							<label htmlFor="weeks">Weeks</label>
							<input type="number" id="weeks" className="form-control"  value={this.state.newCourse.weeks} onChange={(e)=>this.handleInput('weeks', e)} />
						</div>
						<div className="form-group">
							<label htmlFor="duration">Duration (minutes)</label>
							<input type="number" id="duration" className="form-control" value={this.state.newCourse.durationMinutes} onChange={(e)=>this.handleInput('durationMinutes', e)} />
						</div>
						<button type="submit" className="btn btn-primary">Save course</button>
					</form>
				</div>
			</div>
			);
	}
}

export default AddCourse;