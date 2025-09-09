import { StyledLabel } from './styles';
import { LabelProps } from './types';

export default function Label({ attrKey, attributes }: LabelProps) {
    
    return (
        <StyledLabel>{ (attributes as Record<string, any>)[attrKey] }</StyledLabel>
    );
}