import React, { Component } from 'react';
import { put, get } from './Api';
import { Loading } from './Utilities';

class UserProfile extends Component {
	constructor(props) {
		super(props);
		this.state = {
			user: null,
            headline: null,
            uiState: null
		};

		this.handleSubmit = this.handleSubmit.bind(this);
		this.handleInput = this.handleInput.bind(this);
	}

    componentDidMount() {
	    this.setState({uiState: 'loading'});
        get('/api/users/current').then(user => {
            this.setState({ user, headline: user.name });
            this.setState({uiState: 'ready'});
        });
    }

	handleInput(key, e) {
	    var value = e.target === undefined ? e : e.target.value;
		var state = Object.assign({}, this.state.user);
    	state[key] = value;
    	this.setState({user: state, uiState: 'ready' });
	}

	handleSubmit(e) {
		e.preventDefault();

        this.setState({uiState: 'saving'});

		let data = {
			name: this.state.user.name,
			birthDate: this.state.user.birthDate,
			gender: this.state.user.gender
		};

        put('/api/users/'+this.state.user.id, data)
            .then(() => this.setState({uiState: 'saved'}))
            .catch(() => this.setState({uiState: 'error'}));
	}

	render() {
        if (this.state.user == null) {
            return <Loading />;
        }
        const { name, birthDate, gender, email } = this.state.user;
        const uiState = this.state.uiState;
        const buttonText =
            uiState === 'saving' ? "Saving..." :
            uiState === 'saved' ? 'Saved!' :
                'Save information';
		return (
            <form onSubmit={this.handleSubmit}>
                <h1>{this.state.headline}</h1>
                <div className="form-group">
                    <label htmlFor="name">Name</label>
                    <input type="text" required id="name" className="form-control" value={name} onChange={(e)=>this.handleInput('name', e)} />
                </div>
                <div className="form-group">
                    <label htmlFor="email">Email</label>
                    <input type="text" readOnly className="form-control-plaintext" value={email} />
                </div>
                <div className="form-group">
                    <label htmlFor="birthDate">Birth date</label>
                    <input type="date" required id="birthDate" className="form-control" value={birthDate} onChange={(e)=>this.handleInput('birthDate', e)} />
                </div>
                <div className="form-group">
                    <label>Gender</label>
                    <div className="form-check">
                        <input className="form-check-input" type="radio" name="gender" id="gender_male"
                               value="male" onChange={(e) => this.handleInput('gender', e)}
                               checked={gender === "male"} required />
                        <label className="form-check-label" htmlFor="gender_male">Male</label>
                    </div>
                    <div className="form-check">
                        <input className="form-check-input" type="radio" name="gender" id="gender_female"
                               value="female" onChange={(e) => this.handleInput('gender', e)}
                               checked={gender === "female"} required />
                        <label className="form-check-label" htmlFor="gender_female">Female</label>
                    </div>
                </div>
                {this.state.uiState === 'error' && <div className="alert alert-danger">An error occurred while saving your information.</div>}
                <button type="submit" className={uiState === 'saved' ? "btn btn-success" : "btn btn-primary"}>{buttonText}</button>
            </form>
        );
	}
}

export default UserProfile;