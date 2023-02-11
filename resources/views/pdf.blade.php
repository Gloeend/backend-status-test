<!DOCTYPE html>
<html>
<head>
    <title>PDF</title>
</head>
<style type="text/css">
* {
  font-family: "DejaVu Sans", sans-serif;
}
</style>
<body>
    <div class="container">
        <ul>
            <li>Цвет покраски: {{ $data['painting'] }}</li>
            <li>Цвет пленки: {{ $data['film'] }}</li>
            <li>Цвет ручки: {{ $data['handle'] }}</li>
            <li>Ширина: {{ $data['width'] }}</li>
            <li>Высота: {{ $data['height'] }}</li>
            <li>Открывание: {{ $data['opening'] ? 'Правое' : 'Левое' }}</li>
            <li>Аксессуары: {{ count($data['accessories']) > 0 ? implode(', ', $data['accessories']) : 'Нету' }}</li>
            <li>Цена: {{ $data['price'] }}</li>
        </ul>
    </div>
</body>
</html>