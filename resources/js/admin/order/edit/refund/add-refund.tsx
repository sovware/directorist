import { Button } from "@wpmvc/components";
import React from "react";
import styled from "styled-components";
import Controls from "../../../controls";

const RefundSubmission = styled.div``;

export default function AddRefund({handleSubmitRefund, refundFields, attributes, setAttributes, errors, setErrors}){
    return( 

        <RefundSubmission>
                <form action="" onSubmit={handleSubmitRefund} >
                    <Controls
                        fields={refundFields}
                        attributes={attributes}
                        setAttributes={setAttributes}
                        errors={errors}
                        setErrors={setErrors}
                    />
                    <Button type="submit" variant="primary" >Submit</Button>
                </form>
                
            </RefundSubmission>
    )
}