export abstract class Validator {
	abstract validate(
		value: any,
		attributes?: Record<string, any>
	): string | null;
}
