import React from "react";
import Card from '../../../card.tsx';
import Layout from './layout.tsx';

export default function Feature(){
    const renderLeftContent = ()=>{
        return(
            <>
                <Card title="Feature" />
            </>
        )
    }


    const renderRightContent = ()=>{
        return(
            <>
                <Card title="Feature" />
            </>
        )
    }

    return(
        <Layout 
            views={
                {
                    leftContent: renderLeftContent(),
                    rightContent: renderRightContent()
                }
            }
        />
    ) 
}
