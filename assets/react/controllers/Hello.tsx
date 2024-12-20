import React from 'react';

interface HelloProps {
    fullName: string;
}
export default function (props: HelloProps) {
    return <div className="bg-amber-600">Hello {props.fullName} Comment tu vas gros bg</div>;
}
