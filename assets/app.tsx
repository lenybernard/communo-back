import * as React from "react";
import * as ReactDOM from "react-dom";
import {Button, Col, Row, Tab, Tabs} from "react-bootstrap";

ReactDOM.render(
    <div>
        <h1>Hello, Welcome to React and TypeScript</h1>
        <Tabs defaultActiveKey="profile" id="uncontrolled-tab-example" className="mb-3">
            <Tab eventKey="home" title="Home">
                1
            </Tab>
            <Tab eventKey="profile" title="Profile">
                2
            </Tab>
            <Tab eventKey="contact" title="Contact" disabled>
                3
            </Tab>
        </Tabs>
        <Row className="mx-0">
            <Button as={Col} variant="primary">Button #1</Button>
            <Button as={Col} variant="secondary" className="mx-2">Button #2</Button>
            <Button as={Col} variant="success">Button #3</Button>
        </Row>
    </div>,
    document.getElementById("root")
);