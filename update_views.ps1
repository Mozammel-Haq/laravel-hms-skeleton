$files = Get-ChildItem -Path "resources/views" -Recurse -Filter "*.blade.php"
foreach ($file in $files) {
    try {
        $content = Get-Content -Path $file.FullName -Raw
        if ($content -match '<div class="table">') {
            $newContent = $content -replace '<div class="table">', '<div class="table-responsive">'
            Set-Content -Path $file.FullName -Value $newContent -NoNewline
            Write-Host "Updated $($file.FullName)"
        }
    } catch {
        Write-Host "Error processing $($file.FullName): $_"
    }
}
