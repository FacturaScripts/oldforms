<?php
/**
 * This file is part of OldForms plugin for FacturaScripts
 * Copyright (C) 2017-2022 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace FacturaScripts\Plugins\OldForms\Controller;

use FacturaScripts\Plugins\OldForms\GridForms\PurchaseDocumentController;

/**
 * Controller to edit a single item from the AlbaranProveedor model
 *
 * @author Carlos García Gómez      <carlos@facturascripts.com>
 * @author Francesc Pineda Segarra  <francesc.pineda.segarra@gmail.com>
 */
class EditAlbaranProveedor extends PurchaseDocumentController
{

    public function getModelClassName(): string
    {
        return 'AlbaranProveedor';
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data['menu'] = 'purchases';
        $data['title'] = 'delivery-note';
        $data['icon'] = 'fas fa-dolly-flatbed';
        return $data;
    }
}
