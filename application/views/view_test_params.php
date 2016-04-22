<div class="test_container">
    <div class="test_area">
        <div class="nav_container">
            <form method="post" action="/tests/<?php echo $data['action']; ?>">
                <div>Категорія</div>
                <select name="wordsCategory">
                    <option value="all">All</option>
                    <?php foreach($data['cats'] as $val) { ?>
                    <option value="<?php echo $val['category_name']; ?>"><?php echo $val['category_name']; ?></option>
                    <?php } ?>
                </select>
                <br />
                <div>Кількість слів</div>
                <select name="wordsNumber">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                </select>
                <br />
                <button type="submit" name="start_test">Start!</button>
            </form>
        </div>
    </div>
</div>