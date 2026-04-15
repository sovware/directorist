import { useEffect, useState } from '@wordpress/element';
import { getQueryArg } from '@wordpress/url';

function getIdFromLocation(): string | undefined {
	if ( typeof window === 'undefined' ) return undefined;

	const href = window.location.href;
	if ( ! href ) return undefined;

	// Try query params first
	const fromQuery = getQueryArg( href, 'id' ) as string | null;
	if ( fromQuery ) return fromQuery;

	// Fallback: extract last segment of path
	const url = new URL( href );
	const segments = url.href.split( '/' ).filter( Boolean );

	return segments.pop();
}

export function useGetId(): string | undefined {
	const [ id, setId ] = useState< string | undefined >( getIdFromLocation );

	useEffect( () => {
		const handleLocationChange = () => setId( getIdFromLocation() );

		window.addEventListener( 'hashchange', handleLocationChange );
		window.addEventListener( 'popstate', handleLocationChange );

		return () => {
			window.removeEventListener( 'hashchange', handleLocationChange );
			window.removeEventListener( 'popstate', handleLocationChange );
		};
	}, [] );

	return id;
}
