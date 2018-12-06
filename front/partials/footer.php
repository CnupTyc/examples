</div>

<footer></footer>

<?// JS'ки подключаются так, чтобы к ним добавлялось время последней модификации
$jsFileList = ['common'];

if ($pageType) {
    $jsFileList[] = $pageType;
}

foreach ($jsFileList as $jsFilename):
    $jsFilePath = "js/bundle/$jsFilename.bundle.js";
    ?><script src="<?= $jsFilePath . '?' . filemtime($jsFilePath)?>"></script><?
endforeach; 

?>
</body>
</html>
