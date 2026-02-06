# Class 10: Deep Dive - BaseTenantModel

## Introduction
The `BaseTenantModel` is an abstract class that acts as the parent for all our entity models.

## 1. Why an Abstract Base Class?
We could just add `use BelongsToClinic;` to every model. So why create a base class?
1.  **Consistency**: It guarantees that every model extending it *must* have the trait.
2.  **Shared Logic**: If we later want to add another feature to all tenant models (e.g., a specific date format or a shared helper method), we add it in one place.
3.  **Type Hinting**: We can type-hint `BaseTenantModel $model` in our services if we need to accept any tenant-specific model.

## 2. Implementation Review

```php
abstract class BaseTenantModel extends Model
{
    use BelongsToClinic;
}
```

## 3. Extending the Base
When we create the `Patient` model later, it will look like this:

```php
class Patient extends BaseTenantModel
{
    // No need to use BelongsToClinic here, it's inherited.
}
```

## 4. Pitfalls to Avoid
-   **Forgetting to extend**: If you extend `Model` instead of `BaseTenantModel`, you create a security hole. The data will be visible to everyone.
-   **Table Names**: Standard Laravel naming applies. `Patient` -> `patients`.

## Summary
The `BaseTenantModel` is a simple but powerful architectural decision. It serves as a marker for "This data is private to a clinic".

In the next class, we will write the Middleware that initializes the context.
