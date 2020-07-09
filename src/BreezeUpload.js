import React, {Component} from 'react'
import Axios from 'axios';
import {Segment, Header, Icon, Button, Input} from 'semantic-ui-react'

class BreezeUpload extends Component {

    state = {
        selectedFile: null
    }

    fileSelectedHandler = e => {
        const file = e.target.files[0];
        this.setState({selectedFile: file});
    }

    fileUploadHandler(e) {
        e.preventDefault();
        let formData = new FormData();
        formData.append('people_file', this.state.selectedFile, this.state.selectedFile.name);
        Axios.post('http://localhost:8000/api/people', formData).then(res => {
            console.log('response = ', res);
        }).catch(e => {
            console.log('__> BREEZE_ERROR: ', e);
        })
    }

    render() {
        return (
            <Segment placeholder>
                <Header icon>
                    <Icon name='file outline'/>
                    {/*No documents are listed for this customer.*/}
                </Header>
                <Input
                    action={{
                        color: 'blue',
                        position: 'right',
                        // labelPosition: 'right',
                        content: 'upload',
                    }}
                    actionPosition='left'
                    placeholder='upload'
                    defaultValue=''
                    type="file"
                    onSubmit={this.fileUploadHandler}
                />
                {/*<Button primary type="submit">Upload File</Button>*/}
            </Segment>
        );
    }
}

export default BreezeUpload;