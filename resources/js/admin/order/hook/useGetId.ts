import { useMemo } from '@wordpress/element';
import { getQueryArg } from '@wordpress/url';

export function useGetId(): string | undefined {
    return useMemo(() => {
      if (typeof window === "undefined") return undefined;
  
      const href = window.location.href;
      if (!href) return undefined;
  
      // Try query params first
      const fromQuery =
        (getQueryArg(href, "id") as string | null)
  
      if (fromQuery) return fromQuery;
  
      // Fallback: extract last segment of path
      const url = new URL(href);
      const segments = url.href.split("/").filter(Boolean);
      
      return segments.pop();
    }, []);
  }