import React, {Component} from 'react'
import Axios from 'axios';
import {Segment, Header, Icon, Form} from 'semantic-ui-react'

class BreezeUpload1stAttempt extends Component {
    state = {
        selectedFile: null
    }

    constructor(props) {
        super(props);

        // 1st technique to bind to class scope
        this.fileUploadHandler = this.fileUploadHandler.bind(this);
        this.fileSelectedHandler = this.fileSelectedHandler.bind(this);
    }

    fileSelectedHandler(e) {
        const file = e.target.files[0];
        this.setState({selectedFile: file});

        console.log(`_> state selected file = ${this.state.selectedFile}`, this.state);
        console.log('_> file = ', file);
    }

    fileUploadHandler(e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append('people_file', this.state.selectedFile, this.state.selectedFile.name);

        Axios.post('http://127.0.0.1:8000/files/people', formData).then(res => {
            console.log('response = ', res);
        }).catch(err => {
            console.log('__> BREEZE_ERROR: ', err);
        });

    }

    render() {
        return (
            <Segment placeholder>
                <Header icon className="breeze">
                    <Icon name='file outline'/> Breeze ChMS File Uploader
                </Header>

                <Form className="breeze" onSubmit={this.fileUploadHandler}>
                    <Form.Group>
                        <Form.Input type="file" onChange={this.fileSelectedHandler} name="people_file"/>
                        <Form.Button color='blue' className="breeze">
                            Upload
                        </Form.Button>
                    </Form.Group>
                </Form>
            </Segment>
        );
    }
}

export default BreezeUpload1stAttempt;