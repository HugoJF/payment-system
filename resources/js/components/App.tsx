import React, {Component} from 'react';
import {Route, Switch, withRouter} from "react-router-dom";
import Container from "./ui/Container";
import {setAccent} from "../actions/UI";
import {connect} from "react-redux";
import NotFound from "./ui/NotFound";
import OrderContainer from "./order-components/OrderContainer";
import {RouteComponentProps} from "react-router";

// TODO: add inside OrderContainer
interface OrderContainerProps {
    orderId: string,
    action: string,
}

class AppComponent extends Component {
    render() {
        return (
            <Container>
                <Switch>
                    <Route
                        path="/orders/:orderId/:action?"
                        render={(props: RouteComponentProps<OrderContainerProps>) => (
                            <OrderContainer
                                orderId={props.match.params.orderId}
                                action={props.match.params.action}
                            />
                        )}
                    />
                    <Route
                        render={() => (
                            <NotFound/>
                        )}
                    />
                </Switch>
            </Container>
        );
    }
}

const mapDispatchToProps = dispatch => ({});

const mapStateToProps = state => ({});

export default withRouter(connect(
    mapStateToProps, mapDispatchToProps
)(AppComponent));