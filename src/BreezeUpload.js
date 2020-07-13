import React, {Component} from 'react'
import Axios from 'axios';
import {Segment, Header, Icon, Form} from 'semantic-ui-react'

class BreezeUpload extends Component {
    state = {
        selectedFile: null,
        csv_type: null
    }

    // arrow function for this to ref class scope
    fileSelectedHandler = (e) => {
        const file = e.target.files[0];
        this.setState({selectedFile: file});

        console.log(`_> state selected file = ${this.state.selectedFile}`, this.state);
        console.log('_> file = ', file);
    }

    // arrow function for this to ref class scope
    fileUploadHandler = (e) => {
        //e.preventDefault();

        let formData = new FormData();
        let inputName = `${this.props.csvtype}_file`;
        formData.append(inputName, this.state.selectedFile, this.state.selectedFile.name);

        Axios.post('http://127.0.0.1:8000/api/files', formData).then(res => {
            console.log('response = ', res);
        }).catch(err => {
            console.log('__> JULIUS_ERROR: ', err);
        });

        let debug = 1;
    }

    render() {
        return (
            <div>
                <form>
                    <input type="file" onChange={this.fileSelectedHandler}/>
                    <button onClick={this.fileUploadHandler}>upload</button>
                </form>
            </div>
        );
    }
}

export default BreezeUpload;